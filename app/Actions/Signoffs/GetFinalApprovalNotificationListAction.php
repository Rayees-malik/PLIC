<?php

namespace App\Actions\Signoffs;

use App\DataTransferObjects\SignoffStepData;
use App\Helpers\SignoffFlowStateHelper;
use App\Models\Role;
use App\User;
use Illuminate\Support\Collection;

class GetFinalApprovalNotificationListAction
{
    public function execute(SignoffStepData $data): Collection
    {
        if ($data->signoffFlowState != SignoffFlowStateHelper::APPROVED) {
            return collect();
        }

        $signoff = $data->signoff;
        $notification = class_basename($signoff->proposed);
        $notificationType = $data->notification->getSignoffNotificationType();

        $finalApprovalTo = collect($signoff->signoffConfig->final_approval_to);

        // 1. emails, 2. users, 3. roles
        $finalApprovalUsers = $finalApprovalTo
            ->filter(fn ($item, $key) => $key == $notificationType)
            ->pluck('emails')
            ->flatMap(function ($item, $key) {
                return User::select('id', 'email', 'unsubscriptions')->whereIn('email', $item)->get();
            })
            ->merge(
                $finalApprovalTo
                    ->filter(fn ($item, $key) => $key == $notificationType)
                    ->pluck('users')
                    ->flatten(1)
                    ->map(function ($item, $key) use ($signoff) {
                        $relations = explode('.', $item);
                        $relation = $signoff->proposed;

                        foreach ($relations as $link) {
                            $relation = $relation->$link;
                        }

                        return $relation;
                    })
            )
            ->merge(
                $finalApprovalTo
                    ->filter(fn ($item, $key) => $key == $notificationType)
                    ->pluck('roles')
                    ->flatMap(function ($item, $key) {
                        $roles = Role::select('id', 'name')
                            ->with('users:id,email,name,unsubscriptions')
                            ->whereIn('name', $item ?? [])
                            ->get()
                            ->flatMap(fn ($item, $key) => $item->users);

                        return $roles;
                    })
            )
            ->filter()
            ->whenNotEmpty(
                fn ($collection) => $collection->filter(fn ($user) => $user->wantsMailNotification($notification))
            );

        return $finalApprovalUsers;
    }
}
