<?php

namespace App\Notifications\Signoffs;

use App\Notifications\SignoffNotification;

class SignoffApproved extends SignoffNotification
{
    public function getSubject(): string
    {
        return "{$this->resolveType()} Submission Approved";
    }

    public function getHeaderText(): string
    {
        return "Your {$this->resolveType()} submission was approved.";
    }

    public function getSummaryData(): array
    {
        if ($this->comment) {
            $this->summaryData['Comment'] = $this->comment;
        }

        return $this->summaryData;
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

        $arrayData['title'] = "{$modelType} Approved";
        $arrayData['body'] = $this->getBody($arrayData);
        $arrayData['status'] = 'success';

        return $arrayData;
    }
}
