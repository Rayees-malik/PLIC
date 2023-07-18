<?php

namespace App\Actions\Signoffs;

use App\DataTransferObjects\SignoffStepData;
use App\Models\SignoffResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UpdateSignoffStepAction
{
    public function __construct(private ProcessSignoffAction $processSignoffAction)
    {
    }

    public function execute(SignoffStepData $data, Request $request): ?RedirectResponse
    {
        $model = $data->signoff->proposed;

        $clone = clone $data;

        $formRequest = $data->signoff->stepConfig->form_request;

        if ($formRequest) {
            if (class_exists($formRequest)) {
                $formRequest = app($formRequest);

                // Update Model
                if (method_exists($model, 'extraUpdates')) {
                    $formData = $model::modifyFormData($formRequest->partialValidated()->validated, $model);
                    $model->update($formData);
                    $model->extraUpdates($formRequest);
                    $model->uploadFiles($formData);
                    // $formData = $model::modifyFormData($data->formRequest->partialValidated()->validated, $model);

                    // $model->update($formData);
                    // $model->extraUpdates($data->formRequest);
                    // $model->uploadFiles($formData);

                    // Always allow rejecting
                    if ($data->action !== 'reject' && $model->formErrors && ! $model->formErrors->allBagsEmpty()) {
                        // Allow saving comments even when there are errors
                        if (! empty($data->comment)) {
                            SignoffResponse::saveResponse($clone->signoff, true, $data->comment, true);
                        }

                        return redirect()->route('signoffs.edit', $data->signoff->id)->withInput()->with(['errors' => $model->formErrors]);
                    }
                } else {
                    $formData = $model::modifyFormData($formRequest->validated(), $model);
                    $model->update($formData);
                    $model->uploadFiles($formData);
                }
            }
        } else {
            // Use Stepper Handling
            $result = $model::stepperUpdate($request);

            $clone->errors = $result->errors;

            // DEV: Comment out for easier of testing.
            // Always allow rejecting
            if ($data->action !== 'reject' && ! $result->errors->allBagsEmpty()) {
                // Allow saving comments even when there are errors
                if (! empty($data->comment)) {
                    $clone->response = SignoffResponse::saveResponse($clone->signoff, true, $data->comment, true);
                }

                return redirect()->route('signoffs.edit', $data->signoff->id)->withInput()->with(['errors' => $result->errors]);
            }
        }

        switch ($data->action) {
            case 'approve':
                $this->processSignoffAction->execute($clone);
                flash("Approval submitted for {$clone->signoffType}.", 'success');
                break;
            case 'reject':
                $this->processSignoffAction->execute($clone);
                flash("Rejection submitted for {$clone->signoffType}.", 'warning');
                break;
            default:
                if (! empty($data->comment)) {
                    SignoffResponse::saveResponse($clone->signoff, true, $data->comment, true);
                }

                flash("Successfully saved changes to {$clone->signoffType}.", 'success');

                return redirect()->back();
        }

        return null;
    }
}
