<?php

namespace App\Actions\Signoffs;

use App\DataTransferObjects\SignoffStepData;

class GoToNextSignoffStepAction
{
    public function execute(SignoffStepData $data): void
    {
        if ($data->nextStep != $data->step) {
            if ($data->action == 'approve') {
                $data->signoff->gotoNextStep();
            } else {
                $data->signoff->gotoPrevStep();
            }
        }
    }
}
