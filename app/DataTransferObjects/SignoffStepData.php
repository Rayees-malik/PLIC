<?php

namespace App\DataTransferObjects;

use App\Http\Requests\Signoffs\UpdateSignoffFormRequest;
use App\Models\Signoff;
use App\Models\SignoffResponse;
use App\Notifications\SignoffNotification;
use App\SteppedViewErrorBag;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\DataTransferObject\DataTransferObject;

class SignoffStepData extends DataTransferObject
{
    public string $action;

    public ?bool $newSubmission;

    public int $step;

    public User $user;

    public ?SteppedViewErrorBag $errors = null;

    public ?FormRequest $formRequest = null;

    public ?string $signoffType;

    public ?Signoff $signoff;

    public ?string $comment;

    public ?int $nextStep;

    public ?SignoffResponse $response;

    public ?SignoffNotification $notification;

    public ?string $signoffFlowState;

    public static function fromRequest(UpdateSignoffFormRequest $request): SignoffStepData
    {
        return new self([
            'action' => $request->action,
            'comment' => $request->signoff_comment,
            'step' => (int) $request->signoff_step,
            'user' => auth()->user(),
        ]);
    }

    public static function fromSignoffSubmitData(SignoffSubmitData $data): SignoffStepData
    {
        $signoffStepData = new self([
            'action' => 'submit',
            'step' => $data->signoff->step,
            'user' => $data->user,
        ]);

        return $signoffStepData->forSignoff($data->signoff);
    }

    public function forSignoff(Signoff $signoff): SignoffStepData
    {
        $clone = clone $this;

        $clone->signoff = $signoff;
        $clone->signoffType = class_basename($signoff->proposed);
        $clone->newSubmission = (bool) $signoff->new_submission;

        // $clone->formRequest = class_exists($signoff->stepConfig->form_request)
        //     ? app($signoff->stepConfig->form_request)
        //     : null;

        return $clone;
    }

    public function withResponse(SignoffResponse $response): SignoffStepData
    {
        $clone = clone $this;

        $clone->response = $response;

        return $clone;
    }

    public function withNextStep(int $nextStep): SignoffStepData
    {
        $clone = clone $this;

        $clone->nextStep = $nextStep;

        return $clone;
    }

    public function withNotification(SignoffNotification $notification): SignoffStepData
    {
        $clone = clone $this;

        $clone->notification = $notification;

        return $clone;
    }

    public function withErrors(SteppedViewErrorBag $errors): SignoffStepData
    {
        $clone = clone $this;

        $clone->errors = $errors;

        return $clone;
    }
}
