<?php

namespace App\Actions\Signoffs;

use App\DataTransferObjects\SignoffStepData;

class CheckIfSignoffCompleteAction
{
    public function execute(SignoffStepData $data): bool
    {
        return $data->nextStep > $data->signoff->signoffConfigSteps->count() || $data->nextStep == 0;
    }
}
