<?php

namespace App\Actions\Database;

use App\Helpers\SignoffStateHelper;
use App\Models\Signoff;
use Illuminate\Support\Facades\Config;

class DeleteArchivedSignoffsAction
{
    public function execute()
    {
        $days = Config::get('plic.outdated_signoff_cleanup.archived_delay_days');

        Signoff::where('state', SignoffStateHelper::ARCHIVED)
            ->where('updated_at', '<', now()->subDays($days))
            ->delete();
    }
}
