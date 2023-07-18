<?php

namespace App\Actions\Signoffs;

use App\DataTransferObjects\SignoffStepData;
use App\Helpers\SignoffNotificationTypeHelper;
use Illuminate\Support\Str;

class ResolveSignoffNotificationAction
{
    private array $notificationVerbMap = [
        'BrandDiscoRequest' => '', //SignoffNotificationTypeHelper::DISCONTINUATION,
        'ProductDelistRequest' => '', //SignoffNotificationTypeHelper::DISCONTINUATION,
        'InventoryRemoval' => '', //SignoffNotificationTypeHelper::SUBMISSION,
        'MarketingAgreement' => '', //SignoffNotificationTypeHelper::SUBMISSION,
        'PricingAdjustment' => '', //SignoffNotificationTypeHelper::SUBMISSION,
        'Product' => 'resolveProductVerb',
        'Brand' => 'resolveListingOrUpdate',
        'Promo' => 'resolveSubmissionOrUpdate',
        'Retailer' => 'resolveListingOrUpdate',
        'Vendor' => 'resolveListingOrUpdate',
    ];

    public function execute(SignoffStepData $data): SignoffStepData
    {
        return $data->withNotification($this->resolveNotificationClass($data));
    }

    private function resolveListingOrUpdate(SignoffStepData $data)
    {
        if ($data->newSubmission) {
            return SignoffNotificationTypeHelper::LISTING;
        }

        return SignoffNotificationTypeHelper::CHANGE;
    }

    private function resolveSubmissionOrUpdate(SignoffStepData $data)
    {
        if ($data->newSubmission) {
            return ''; //SignoffNotificationTypeHelper::SUBMISSION;
        }

        return SignoffNotificationTypeHelper::CHANGE;
    }

    private function resolveProductVerb(SignoffStepData $data)
    {
        if ($data->newSubmission) {
            return SignoffNotificationTypeHelper::LISTING;
        }

        if ($data->signoff->initial->as400StockData->status == 'D') {
            return SignoffNotificationTypeHelper::RELISTING;
        }

        if ($data->signoff->proposed->unit_cost !== $data->signoff->initial->unit_cost) {
            return SignoffNotificationTypeHelper::PRICE_CHANGE;
        }

        return SignoffNotificationTypeHelper::CHANGE;
    }

    private function resolveNotificationClass(SignoffStepData $data)
    {
        $params = [
            'signoff' => $data->signoff,
            'user' => $data->user,
            'comment' => $data->comment,
            'adjective' => $data->signoffFlowState,
            'verb' => $this->resolveVerb($data),
        ];

        $class = 'App\\Notifications\\Signoffs\\' . $data->signoffType . Str::studly($params['verb']) . 'Notification';

        return resolve($class, $params);
    }

    private function resolveVerb(SignoffStepData $data)
    {
        $value = $this->notificationVerbMap[$data->signoffType];

        if (method_exists($this, $value)) {
            return $this->$value($data);
        }

        return $value;
    }
}
