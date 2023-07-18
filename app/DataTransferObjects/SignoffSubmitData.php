<?php

namespace App\DataTransferObjects;

use App\Models\Signoff;
use App\Models\SignoffResponse;
use App\Notifications\SignoffNotification;
use App\SteppedViewErrorBag;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Spatie\DataTransferObject\DataTransferObject;

class SignoffSubmitData extends DataTransferObject
{
    public string $action;

    public ?bool $newSubmission;

    public ?int $step;

    public ?User $user;

    // public ?SteppedViewErrorBag $errors = null;
    public ?FormRequest $formRequest = null;

    public ?string $signoffType;

    public ?Signoff $signoff;

    // public ?string $comment;
    public ?int $nextStep;

    // public ?SignoffResponse $response;
    public ?SignoffNotification $notification;

    public static function fromRequest(Request $request): SignoffSubmitData
    {
        return new self([
            'action' => $request->action,
            'user' => auth()->user(),
        ]);
    }

    public function forSignoff(Signoff $signoff): SignoffSubmitData
    {
        $clone = clone $this;

        $clone->signoff = $signoff;
        $clone->signoffType = $signoff->proposed::getShortClassName();
        $clone->newSubmission = (bool) $signoff->new_submission;
        $clone->step = $signoff->step;

        // $clone->formRequest = class_exists($signoff->stepConfig->form_request)
        //     ? app($signoff->stepConfig->form_request)
        //     : null;

        return $clone;
    }

    // public function withResponse(SignoffResponse $response): SignoffStepData
    // {
    //     $clone = clone $this;

    //     $clone->response = $response;

    //     return $clone;
    // }

    public function withNextStep(int $nextStep): SignoffSubmitData
    {
        $clone = clone $this;

        $clone->nextStep = $nextStep;

        return $clone;
    }

    public function withNotification(SignoffNotification $notification): SignoffSubmitData
    {
        $clone = clone $this;

        $clone->notification = $notification;

        return $clone;
    }

    // public function withErrors(SteppedViewErrorBag $errors): SignoffStepData
    // {
    //     $clone = clone $this;

    //     $clone->errors = $errors;

    //     return $clone;
    // }
}
