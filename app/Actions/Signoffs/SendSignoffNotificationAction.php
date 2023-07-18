<?php

namespace App\Actions\Signoffs;

use App\DataTransferObjects\SignoffStepData;
use App\Models\Role;

class SendSignoffNotificationAction
{
    public function __construct(private CheckIfSignoffCompleteAction $checkIfSignoffCompleteAction, private GetFinalApprovalNotificationListAction $getFinalApprovalNotificationListAction)
    {
    }

    public function execute(SignoffStepData $data): void
    {
        if ($this->checkIfSignoffCompleteAction->execute($data)) {
            $users = collect([$data->signoff->user]);
            $finalApprovalUsers = $this->getFinalApprovalNotificationListAction->execute($data);

            $users->merge($finalApprovalUsers)
                ->unique('id')
                ->each(fn ($user) => $user->notify($data->notification));

            return;
        }

        $notification = $data->notification;

        if ($notification) {
            $nextStep = $data->signoff->signoffConfig->getStep($data->nextStep);

            if (! $nextStep->approval_to_type) {
                return;
            }

            if ($data->signoffType == 'Promo' && $data->signoff->proposed->period->owner_id) {
                $nextStep->approval_to = 'period.owner.accountManager';
            }

            if ($data->signoffType == 'InventoryRemoval' && $nextStep->approval_to == 'warehouse-qc') {
                $warehouseLookup = [
                    '01' => '01',
                    '50' => '01',
                    '04' => '04',
                    '40' => '04',
                    '08' => '08',
                    '80' => '08',
                    '09' => '09',
                    '90' => '09',
                ];

                $warehouse = $data->signoff->proposed->lineItems()->first()->warehouse;
                $nextStep->approval_to = 'warehouse-' . $warehouseLookup[$warehouse];
            }

            $responses = $data->signoff->responses()->where('step', '=', $data->nextStep)->where('archived', '=', 0)->get()->pluck('user_id');

            switch ($nextStep->approval_to_type) {
                case 'role':
                    Role::where('name', $nextStep->approval_to)
                        ->first()
                        ->users
                        ->reject(
                            function ($user) use ($responses) {
                                return $responses->contains($user->id);
                            }
                        )
                        ->filter(fn ($user) => $user->wantsMailNotification(class_basename($notification->signoff->proposed)))
                        ->each(function ($user) use ($notification) {
                            $user->notify($notification);
                        });
                    break;
                case 'user':
                    $relations = explode('.', $nextStep->approval_to);
                    $relation = $data->signoff->initial;

                    foreach ($relations as $link) {
                        $relation = $relation->$link;
                    }

                    $user = $relation;

                    if ($user->wantsMailNotification(class_basename($notification->signoff->proposed))) {
                        $user->notify($notification);
                    }

                    break;
            }
        }
    }
}
