<?php

namespace App\Notifications\Signoffs;

use App\Notifications\SignoffNotification;

class SignoffRejected extends SignoffNotification
{
    public function getSubject(): string
    {
        return "{$this->signoff->proposed->getShortClassName()} Submission Rejected";
    }

    public function getHeaderText(): string
    {
        return "Your {$this->resolveType()} submission was rejected. Please log in to review the reason(s) and resubmit.";
    }

    public function getSummaryData(): array
    {
        if ($this->comment) {
            $this->summaryData['Comment'] = $this->comment;
        }

        $this->summaryData['Rejected By'] = $this->user->name;

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
            'action' => route('signoffs.edit', $this->signoff->id),
        ];

        $arrayData['title'] = "{$modelType} Rejected";
        $arrayData['body'] = $this->getBody($arrayData);
        $arrayData['status'] = 'danger';

        return $arrayData;
    }
}
