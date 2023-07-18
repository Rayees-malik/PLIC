<?php

namespace App\Actions\Signoffs;

use App\DataTransferObjects\SignoffStepData;

class ProcessSignoffAction
{
    public function __construct(private SaveSignoffResponseAction $saveResponseAction, private DetermineSignoffNextStepAction $determineSignoffNextStepAction, private ResolveSignoffFlowStateAction $resolveSignoffFlowStateAction, private ResolveSignoffNotificationAction $resolveSignoffNotificationAction, private SendSignoffNotificationAction $sendSignoffNotificationAction, private GoToNextSignoffStepAction $goToNextSignoffStepAction)
    {
    }

    public function execute(SignoffStepData $data): void
    {
        $data = $this->saveResponseAction->execute($data);
        $data = $this->determineSignoffNextStepAction->execute($data);
        $data = $this->resolveSignoffFlowStateAction->execute($data);
        $data = $this->resolveSignoffNotificationAction->execute($data);
        $this->sendSignoffNotificationAction->execute($data);
        $this->goToNextSignoffStepAction->execute($data);
    }
}
