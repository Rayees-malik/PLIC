<?php

namespace App\Datatables;

use Illuminate\Support\Arr;

class NotificationsDatatable extends BaseDatatable
{
    public function __construct(protected $notifications)
    {
    }

    public function datatable($query)
    {
        return datatables($query)
            ->editColumn('data', function ($notification) {
                return Arr::get($notification->data, 'body') ?? 'Notice';
            })
            ->editColumn('created_at', function ($notification) {
                return $notification->created_at->toDateTimeString();
            })
            ->addColumn('action', function ($notification) {
                $dismiss = $notification->read_at ? '' : "<button class=\"link-btn table-btn js-close-notification\" data-id=\"{$notification->id}\"><i class=\"material-icons\">done</i>Dismiss</button>";
                $read = '<a href="' . route('notifications.read', $notification->id) . '" class="link-btn table-btn"><i class="material-icons">read_more</i>View</a>';

                return "{$dismiss}{$read}";
            })
            ->rawcolumns(['data', 'action']);
    }

    public function query()
    {
        return $this->notifications;
    }

    protected function getColumns()
    {
        return [
            [
                'title' => 'Message',
                'data' => 'data',
            ],
            [
                'title' => 'Timestamp',
                'data' => 'created_at',
                'searchable' => false,
            ],
            [
                'title' => '',
                'data' => 'action',
                'orderable' => false,
                'searchable' => false,
                'exportable' => false,
                'printable' => false,
            ],
        ];
    }
}
