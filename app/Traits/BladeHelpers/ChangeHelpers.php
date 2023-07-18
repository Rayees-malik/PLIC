<?php

namespace App\Traits\BladeHelpers;

use Illuminate\Support\Arr;

const CHANGE_TYPES_ID = [
    'pivot',
    'pivot_data',
    'contact',
    'child_concat',
    'media',
    'lineitem',
];

trait ChangeHelpers
{
    public static function writeModelChanges($properties)
    {
        $jsProperties = [];

        $tables = [];
        $type = 'default';
        foreach ($properties as $property => $changes) {
            if (! is_array($changes)) {
                continue;
            }

            $type = Arr::get($changes, 'type');
            unset($changes['type']);

            if (in_array($type, CHANGE_TYPES_ID)) {
                foreach ($changes as $id => $change) {
                    $tables["{$property}-{$id}"] = $change;
                    $jsProperties["{$property}-{$id}"] = ['type' => $type, 'id' => $id, 'property' => $property];
                }
            } else {
                $tables[$property] = $changes;
                $jsProperties[$property] = ['type' => $type];
            }
        }

        $jsChanges = file_get_contents(dirname(__FILE__) . '/changehistory/jsWrapper.js', false);
        $jsChanges = str_replace('$changes', json_encode($jsProperties), $jsChanges);

        $html = $jsChanges;

        $html .= view('partials.helpers.change-history')->with(['tables' => $tables])->render();

        return $html;
    }
}
