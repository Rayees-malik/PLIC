<?php

namespace App\Actions\Signoffs;

use App\DataTransferObjects\SignoffStepData;
use App\Models\SignoffResponse;

class SaveSignoffResponseAction
{
    public function execute(SignoffStepData $data): SignoffStepData
    {
        if ($data->action === 'approve') {
            return $data->withResponse(SignoffResponse::saveResponse($data->signoff, true, $data->comment));
        }

        if ($data->action === 'reject') {
            return $data->withResponse(SignoffResponse::saveResponse($data->signoff, false, $data->comment));
        }

        return $data;
    }
}
