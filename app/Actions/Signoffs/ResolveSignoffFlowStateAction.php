<?php

namespace App\Actions\Signoffs;

use App\DataTransferObjects\SignoffStepData;
use App\Helpers\SignoffFlowStateHelper;

class ResolveSignoffFlowStateAction
{
    public function __construct(private CheckIfSignoffCompleteAction $checkIfSignoffCompleteAction)
    {
    }

    public function execute(SignoffStepData $data): SignoffStepData
    {
        if ($data->action === 'reject') {
            $data->signoffFlowState = SignoffFlowStateHelper::REJECTED;

            return $data;
        }

        if ($this->checkIfSignoffCompleteAction->execute($data) && $data->action === 'approve') {
            $data->signoffFlowState = SignoffFlowStateHelper::APPROVED;

            return $data;
        }

        if ($data->action == 'submit') {
            $data->signoffFlowState = SignoffFlowStateHelper::SUBMITTED;

            return $data;
        }

        $data->signoffFlowState = SignoffFlowStateHelper::PENDING;

        return $data;
    }
}
