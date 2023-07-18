<?php

namespace App\Actions\Signoffs;

use App\DataTransferObjects\SignoffStepData;
use App\DataTransferObjects\SignoffSubmitData;

class SubmitSignoffAction
{
    public function __construct(private DetermineSignoffNextStepAction $determineSignoffNextStepAction, private ResolveSignoffFlowStateAction $resolveSignoffFlowStateAction, private ResolveSignoffNotificationAction $resolveSignoffNotificationAction, private SendSignoffNotificationAction $sendSignoffNotificationAction)
    {
    }

    public function execute(SignoffSubmitData $submitData): void
    {
        $data = SignoffStepData::fromSignoffSubmitData($submitData);

        $data = $this->determineSignoffNextStepAction->execute($data);
        $data = $this->resolveSignoffFlowStateAction->execute($data);
        $data = $this->resolveSignoffNotificationAction->execute($data);
        $this->sendSignoffNotificationAction->execute($data);
    }
}
