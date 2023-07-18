<?php

namespace App\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait HasChanges
{
    protected $changeDateFormat = 'M j, Y, g:i a';

    public function getAllChanges($lookups = [])
    {
        $propertiesToIgnore = array_filter([
            $this->getCreatedAtColumn(),
            $this->getUpdatedAtColumn(),
            method_exists($this, 'stateField') ? $this->stateField() : null,
        ]);

        $prefix = '';
        if (method_exists($this, 'changePrefix')) {
            $prefix = $this->changePrefix();
        }

        $ledgers = $this->ledgers()->with('user')->get();
        $created = [];
        $previous = [];
        $changes = [];
        foreach ($ledgers as $ledger) {
            switch ($ledger->event) {
                case 'created':
                    $changeType = 'default';
                    if ($changeType == 'default' && property_exists($this, 'changeTypeOverride')) {
                        $changeType = $this->changeTypeOverride;
                    }
                    if ($changeType == 'media') {
                        if (! Arr::get($ledger->properties, 'cloned_from_id')) {
                            $id = Arr::get($ledger->properties, 'id');
                            $modified = 'media';
                            $key = "{$modified}.{$id}";

                            Arr::set($changes, "{$modified}.type", $changeType);
                            Arr::set($changes, $key, [[
                                'user' => $ledger->user->name,
                                'timestamp' => date($this->changeDateFormat, strtotime($ledger->created_at)),
                                'value' => 'Uploaded',
                            ]]);
                        }
                    }

                    $created = $ledger;
                    $previous = $ledger->properties;
                    break;
                case 'synced':
                    $relation = $ledger->pivot['relation'];
                    $snakeRelation = Str::snake($relation);
                    $pivotType = null;

                    $relationModel = $this->$relation()->getRelated();
                    if (property_exists($this, 'pivotOverrides')) {
                        $pivotType = Arr::get($this->pivotOverrides, $snakeRelation);
                        if ($pivotType == 'ignore') {
                            break;
                        }
                    }
                    if (! $pivotType) {
                        $pivotType = method_exists($relationModel, 'getPivotType') ? $relationModel->getPivotType() : 'pivot';
                    }

                    $pivotData = $this->getPivotChangeData($this->$relation(), $ledger->pivot['properties']);
                    if ($pivotType == 'concat') {
                        if (! Arr::has($changes, $snakeRelation)) {
                            $startData = [];
                            foreach (Arr::wrap(Arr::get($created, "extra.{$snakeRelation}")) as $key => $change) {
                                $startData[] = $this->getPivotChangeValue($change, $key, $snakeRelation, $lookups, $pivotType);
                            }
                            Arr::set($changes, $snakeRelation, [
                                'type' => $pivotType,
                                [
                                    'user' => '',
                                    'timestamp' => 'Starting Value',
                                    'value' => implode(', ', $startData),
                                ]]);
                        }

                        $syncData = [];
                        foreach ($pivotData as $key => $pivotRow) {
                            $syncData[] = $this->getPivotChangeValue($pivotRow, $key, $snakeRelation, $lookups, $pivotType);
                        }
                        $changes[$snakeRelation][] = [
                            'user' => $ledger->user->name,
                            'timestamp' => date($this->changeDateFormat, strtotime($ledger->created_at)),
                            'value' => implode(', ', $syncData),
                        ];
                    } else {
                        foreach ($pivotData as $key => $pivotRow) {
                            if (! Arr::has($previous, $snakeRelation)) {
                                Arr::set($previous, $snakeRelation, Arr::get($created, "extra.{$snakeRelation}"));
                            }
                            $prevRow = Arr::get($previous, "{$snakeRelation}.{$key}");

                            // Ensure value has changed
                            if (Arr::get($pivotRow, 'set') == Arr::get($prevRow, 'set') && Arr::get($pivotRow, 'pivot') == Arr::get($prevRow, 'pivot')) {
                                continue;
                            }

                            if (! Arr::has($changes, "{$snakeRelation}.{$key}")) {
                                Arr::set($changes, "{$snakeRelation}.type", $pivotType);
                                Arr::set($changes, "{$snakeRelation}.{$key}", [[
                                    'user' => '',
                                    'timestamp' => 'Starting Value',
                                    'value' => $this->getPivotChangeValue(Arr::get($previous, "{$snakeRelation}.{$key}"), $key, $snakeRelation, $lookups, $pivotType),
                                ]]);
                            }

                            $changes[$snakeRelation][$key][] = [
                                'user' => $ledger->user->name,
                                'timestamp' => date($this->changeDateFormat, strtotime($ledger->created_at)),
                                'value' => $this->getPivotChangeValue($pivotRow, $key, $snakeRelation, $lookups, $pivotType),
                            ];

                            Arr::set($previous, "{$snakeRelation}.{$key}", $pivotRow);
                        }

                        // Loop again from the previous values to see if anything was removed
                        // but only for entries with no pivot values
                        if ($pivotType != 'pivot_data' && Arr::has($previous, $snakeRelation)) {
                            foreach (Arr::get($previous, $snakeRelation) as $key => $previousRow) {
                                if ($previousRow['set'] && ! Arr::get($pivotData, "{$key}.set")) {
                                    if (! Arr::has($changes, "{$snakeRelation}.{$key}")) {
                                        Arr::set($changes, "{$snakeRelation}.type", $pivotType);
                                        Arr::set($changes, "{$snakeRelation}.{$key}", [[
                                            'user' => '',
                                            'timestamp' => 'Starting Value',
                                            'value' => 'Yes',
                                        ]]);
                                    }
                                    $changes[$snakeRelation][$key][] = [
                                        'user' => $ledger->user->name,
                                        'timestamp' => date($this->changeDateFormat, strtotime($ledger->created_at)),
                                        'value' => 'No',
                                    ];

                                    // Unset in previous
                                    Arr::set($previous, "{$snakeRelation}.{$key}.set", false);
                                }
                            }
                        }
                    }
                    break;
                case 'deleted':
                case 'restored':
                    $modified = 'deleted';
                    $changeType = 'default';
                    if (property_exists($this, 'pivotOverrides')) {
                        $changeType = Arr::get($this->pivotOverrides, $modified) ?? 'default';
                        if ($changeType == 'ignore') {
                            break;
                        }
                    }
                    if ($changeType == 'default' && property_exists($this, 'changeTypeOverride')) {
                        $changeType = $this->changeTypeOverride;
                    }

                    $key = $modified;
                    if ($changeType == 'contact') {
                        $key = "{$modified}.{$this->id}";
                    } elseif ($changeType == 'media') {
                        $modified = 'media';
                        $key = "{$modified}.{$this->id}";
                    }

                    if (! Arr::has($changes, $key)) {
                        if ($modified == $key) {
                            Arr::set($changes, $key, ['type' => $changeType]);
                        } else {
                            Arr::set($changes, "{$modified}.type", $changeType);
                            Arr::set($changes, $key, []);
                        }
                    }

                    $keyArray = Arr::get($changes, $key);
                    array_push($keyArray, [
                        'user' => $ledger->user->name,
                        'timestamp' => date($this->changeDateFormat, strtotime($ledger->created_at)),
                        'value' => ucfirst($ledger->event),
                    ]);
                    Arr::set($changes, $key, $keyArray);
                    break;
                default:
                    foreach ($ledger->modified as $modified) {
                        if (in_array($modified, $propertiesToIgnore)) {
                            continue;
                        }

                        if (! array_key_exists($modified, $previous)) {
                            $previous[$modified] = $ledger->properties[$modified];

                            continue;
                        }

                        // Ensure value has changed
                        if ($ledger->properties[$modified] == $previous[$modified]) {
                            continue;
                        }

                        $changeType = 'default';
                        if (property_exists($this, 'pivotOverrides')) {
                            $changeType = Arr::get($this->pivotOverrides, $modified) ?? 'default';
                            if ($changeType == 'ignore') {
                                break;
                            }
                        }
                        if ($changeType == 'default' && property_exists($this, 'changeTypeOverride')) {
                            $changeType = $this->changeTypeOverride;
                        }

                        $key = $modified;
                        if ($changeType == 'contact' || $changeType == 'lineitem') {
                            $key = "{$modified}.{$this->id}";
                        } elseif ($changeType == 'media') {
                            $oldModified = $modified;
                            $modified = 'media';
                            $key = "{$modified}.{$this->id}";
                        } elseif ($changeType == 'promo_lineitem') {
                            $changeType = 'pivot_data';
                            $key = "{$key}.{$this->product_id}";
                        }

                        if ($changeType == 'promo_lineitem_data' || $changeType == 'promo_data') {
                            $data = json_decode($ledger->properties[$modified]) ?? [];

                            $prevData = Arr::get($previous, $modified, null);
                            if ($prevData) {
                                $prevData = json_decode($prevData);
                            }

                            foreach ($data as $key => $value) {
                                $changeKey = $changeType == 'promo_lineitem' ? "{$key}.{$this->product_id}" : $key;
                                $changeType = $this->getCustomFieldPivotType($key, Arr::get($created->extra, 'owner_id'));

                                if ($prevData && $prevData->$key == $value) {
                                    continue;
                                }

                                if ($changeType == 'child_concat') {
                                    $value = implode(', ', $value ?? []);
                                }

                                if (! Arr::has($changes, $changeKey)) {
                                    $createdData = json_decode($created->properties[$modified]);
                                    $createdValue = $createdData && property_exists($createdData, $key) ? $createdData->$key : '';

                                    if ($changeType == 'child_concat') {
                                        $createdValue = implode(', ', $createdValue ?? []);
                                    }

                                    Arr::set($changes, "{$key}.type", $changeType);
                                    Arr::set($changes, $changeKey, [[
                                        'user' => '',
                                        'timestamp' => 'Starting Value',
                                        'value' => $createdValue,
                                    ]]);
                                }

                                $keyArray = Arr::get($changes, $changeKey);
                                array_push($keyArray, [
                                    'user' => $ledger->user->name,
                                    'timestamp' => date($this->changeDateFormat, strtotime($ledger->created_at)),
                                    'value' => $value,
                                ]);
                                Arr::set($changes, $changeKey, $keyArray);
                            }
                        } else {
                            if (! Arr::has($changes, $key)) {
                                if ($changeType == 'media' && $oldModified = 'custom_properties') {
                                    $properties = json_decode($created->properties['custom_properties']);
                                    if (property_exists($properties, 'label')) {
                                        $value = "Image Label: {$properties->label}";
                                    } else {
                                        $oldProperties = $created->properties;
                                        $oldProperties['custom_properties'] = $ledger->properties['custom_properties'];
                                        $created->properties = $oldProperties;

                                        continue;
                                    }
                                } else {
                                    $value = $created ? (Arr::has($created->extra, $modified) ? $created->extra[$modified] : Arr::get($created->properties, $modified, '')) : '';
                                }

                                if ($modified == $key) {
                                    Arr::set($changes, $key, [
                                        'type' => $changeType,
                                        [
                                            'user' => '',
                                            'timestamp' => 'Starting Value',
                                            'value' => $value,
                                        ]]);
                                } else {
                                    Arr::set($changes, "{$modified}.type", $changeType);
                                    Arr::set($changes, $key, [[
                                        'user' => '',
                                        'timestamp' => 'Starting Value',
                                        'value' => $value,
                                    ]]);
                                }
                            }

                            if ($changeType == 'media' && $modified = 'custom_properties') {
                                $properties = json_decode($ledger->properties[$modified]);
                                if (property_exists($properties, 'label')) {
                                    $value = "Image Label: {$properties->label}";
                                } else {
                                    continue;
                                }
                            } else {
                                $value = Arr::has($ledger->extra, $modified) ? $ledger->extra[$modified] : Arr::get($ledger->properties, $modified, '');
                            }

                            $keyArray = Arr::get($changes, $key);
                            array_push($keyArray, [
                                'user' => $ledger->user->name,
                                'timestamp' => date($this->changeDateFormat, strtotime($ledger->created_at)),
                                'value' => $value,
                            ]);
                            Arr::set($changes, $key, $keyArray);
                        }

                        // Update previous value to keep change check up-to-date
                        $previous[$modified] = $ledger->properties[$modified];
                    }
            }
        }

        if (! empty($prefix)) {
            $prefixedChanges = [];
            foreach ($changes as $key => $value) {
                $prefixedChanges["{$prefix}{$key}"] = $value;
            }
            $changes = $prefixedChanges;
        }

        if (method_exists($this, 'getChangeRelations')) {
            foreach ($this->getChangeRelations() as $relation_name) {
                $relation = call_user_func([$this, $relation_name]);
                // BelongsToMany will be handled by the sync event
                if (! is_a($relation, 'Illuminate\Database\Eloquent\Relations\BelongsToMany')) {
                    $relation->withTrashed()->get()->each(function ($foreign) use (&$changes) {
                        if (method_exists($foreign, 'getAllChanges')) {
                            $changes = array_replace_recursive($changes, $foreign->getAllChanges());
                        }
                    });
                }
            }
        }

        return $changes;
    }

    protected function getPivotChangeData($relation, $properties)
    {
        $model = $relation->getRelated();

        $pivotData = [];
        foreach ($properties as $property) {
            $record = [];
            $id = null;
            foreach ($property as $key => $value) {
                if ($key == $relation->getRelatedPivotKeyName()) {
                    $record['set'] = true;
                    $id = $value;
                } elseif ($key != $relation->getForeignPivotKeyName()) {
                    Arr::set($record, "pivot.{$key}", $value);
                }
            }
            $pivotData[$id] = $record;
        }

        return $pivotData;
    }

    protected function getPivotChangeValue($pivotData, $key, $relation, $lookups, $pivotType)
    {
        $relationModel = null;
        if (Arr::has($lookups, $relation)) {
            $relationModel = Arr::get($lookups, $relation)->find($key);
        }

        if ($pivotType == 'pivot_data' && ! empty(Arr::get($pivotData, 'pivot'))) {
            $pivotData = Arr::wrap(Arr::get($pivotData, 'pivot'));
            if ($relationModel && method_exists($relationModel, 'formatPivotData')) {
                array_walk($pivotData, function (&$value, $key) use ($relationModel) {
                    $value = $relationModel->formatPivotData($value, $key);
                });
            }

            return implode(', ', $pivotData);
        }

        if ($relationModel && method_exists($relationModel, 'getPivotValue')) {
            return $relationModel->getPivotValue(Arr::get($pivotData, 'set'));
        }

        return Arr::get($pivotData, 'set') ? 'Yes' : 'No';
    }
}
