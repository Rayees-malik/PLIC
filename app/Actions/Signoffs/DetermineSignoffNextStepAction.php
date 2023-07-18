<?php

namespace App\Actions\Signoffs;

use App\DataTransferObjects\SignoffStepData;

class DetermineSignoffNextStepAction
{
    public function execute(SignoffStepData $data): SignoffStepData
    {
        $clone = clone $data;

        if (in_array($clone->action, ['approve', 'submit']) && ! $clone->signoff->stepComplete) {
            $clone->nextStep = $clone->step;

            return $clone;
        }

        if ($clone->action == 'approve') {
            return $clone->withNextStep($clone->signoff->proposed->nextStep($clone->step, $clone->signoff));
        } else {
            return $clone->withNextStep($clone->signoff->proposed->prevStep($clone->step, $clone->signoff));
        }
    }
}
