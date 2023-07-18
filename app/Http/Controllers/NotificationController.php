<?php

namespace App\Http\Controllers;

use App\Datatables\NotificationsDatatable;

class NotificationController extends Controller
{
    public function index($filter = null)
    {
        if ($filter == 'read') {
            $notifications = auth()->user()->readNotifications();
            $selected = 'read';
        } else {
            $notifications = auth()->user()->unreadNotifications();
            $selected = 'unread';
        }

        $datatable = new NotificationsDatatable($notifications);

        return $datatable->render('notifications.index', compact('selected'));
    }

    public function read($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect($notification->data['action']);
    }

    public function dismiss($id)
    {
        auth()->user()->unreadNotifications->where('id', $id)->markAsRead();
    }
}
