<?php

namespace App\Models;

use App\Helpers\FormHelper;
use App\RecordableModel;
use App\Traits\HasUserInput;
use Illuminate\Support\Arr;

class Contact extends RecordableModel
{
    use HasUserInput;

    protected static $ignoreFromUserInput = ['role'];

    public $changeTypeOverride = 'contact';

    protected $table = 'contacts';

    protected $guarded = ['id'];

    protected $recordableEvents = [
        'created',
        'updated',
        'restored',
        'deleted',
        'forceDeleted',
    ];

    public static function stepperUpdate($validated, $owner, $isDuplicate = false, $submitting = false)
    {
        $contacts = FormHelper::parseControlArray($validated, 'contact-');
        foreach ($contacts as $contact) {
            $deleted = Arr::get($contact, 'deleted');
            unset($contact['deleted']);
            if (Contact::hasUserInput($contact) || $deleted) {
                if ($deleted && ! $contact['id']) {
                    continue;
                }
                if ($isDuplicate) {
                    $oldId = $contact['id'];
                    unset($contact['id']);
                    if ($deleted) {
                        $savedContact = $owner->contacts()->withTrashed()->where('cloned_from_id', $oldId)->first();
                        if (! $savedContact) {
                            continue;
                        }

                        $savedContact->update($contact);
                    } else {
                        $savedContact = $owner->contacts()->withTrashed()->updateOrCreate(['cloned_from_id' => $oldId], $contact);
                    }
                } else {
                    $savedContact = $owner->contacts()->withTrashed()->updateOrCreate(['id' => $contact['id']], $contact);
                }

                if ($submitting && $deleted && ! $savedContact->cloned_from_id) {
                    return $savedContact->forceDelete();
                } elseif ($deleted && ! $savedContact->trashed()) {
                    $savedContact->delete();
                } elseif (! $deleted && $savedContact->trashed()) {
                    $savedContact->restore();
                }
            }
        }
    }

    public function changePrefix()
    {
        return 'contact-';
    }

    public function getIsTrashedAttribute()
    {
        return $this->trashed() ? '1' : '0';
    }

    public function contactable()
    {
        return $this->morphTo();
    }
}
