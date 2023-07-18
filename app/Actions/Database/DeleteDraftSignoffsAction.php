<?php

namespace App\Actions\Database;

use App\Helpers\SignoffStateHelper;
use App\Models\Signoff;
use Illuminate\Support\Facades\Config;

class DeleteDraftSignoffsAction
{
    public function execute()
    {
        $days = Config::get('plic.outdated_signoff_cleanup.draft_delay_days');

        Signoff::where('state', SignoffStateHelper::IN_PROGRESS)
            ->where('updated_at', '<', now()->subDays($days))
            ->delete();
    }
}
