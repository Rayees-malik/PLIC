<?php

namespace App\Traits;

use Illuminate\Notifications\HasDatabaseNotifications;
use Illuminate\Notifications\RoutesNotifications;

trait Notifiable
{
    use HasDatabaseNotifications, RoutesNotifications;

    public static function dismissAll($users, $id, $type)
    {
        foreach ($users as $user) {
            $user->unreadNotifications->where([
                'data.properties.id' => $id,
                'type' => 'App\Notifications\\' . $type,
            ])->markAsRead();
        }
    }
}
