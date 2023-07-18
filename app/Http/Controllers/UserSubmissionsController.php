<?php

namespace App\Http\Controllers;

use App\Datatables\UserSubmissionsDatatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UserSubmissionsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($filter = null)
    {
        [
            $rejectedCount,
            $pendingCount,
        ] = $this->getIndicators();

        $datatable = new UserSubmissionsDatatable($filter);

        return $datatable->render('users.submissions', [], [
            'datatable' => $datatable,
            'selected' => $filter ?? 'rejected',
            'rejectedCount' => $rejectedCount,
            'pendingCount' => $pendingCount,
        ]);
    }

    private function getIndicators()
    {
        $userId = auth()->id();

        $data = Cache::remember(crc32($userId . '-user-submission-indicators'), 60, function () use ($userId) {
            return DB::query()
                ->addSelect(DB::raw("CASE WHEN EXISTS (select id from signoffs where user_id = {$userId} and deleted_at is null and state = 3) THEN 1 ELSE 0 END AS rejected_count"))
                ->addSelect(DB::raw("CASE WHEN EXISTS (select id from signoffs where user_id = {$userId} and deleted_at is null and state = 0) THEN 1 ELSE 0 END AS pending_count"))
                ->first();
        });

        return [
            $data->rejected_count,
            $data->pending_count,
        ];
    }
}
