<?php

namespace App\Notifications\Signoffs;

class SignoffUnsubmitted extends \App\Notifications\SignoffNotification
{
    public function getSubject(): string
    {
        return "Approved {$this->resolveType()} has been unsubmitted";
    }

    public function getHeaderText(): string
    {
        return "Your {$this->resolveType()} submission has been unsubmitted.";
    }

    public function toArray($notifiable)
    {
        $modelType = $this->signoff->proposed->getShortClassName();
        $arrayData = [
            'type' => class_basename($this),
            'signoff_id' => $this->signoff->id,
            'model_name' => $this->signoff->proposed->displayName,
            'model_type' => $modelType,
            'action' => route('signoffs.show', $this->signoff->id),
        ];

        $arrayData['title'] = "{$modelType} Unsubmitted";
        $arrayData['body'] = $this->getBody($arrayData);

        return $arrayData;
    }
}
