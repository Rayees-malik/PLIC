<?php

namespace Tests\TestSupport\Export;

use App\Contracts\Exports\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;

class QueuedExportable implements Exportable, ShouldQueue
{
    public function export()
    {

    }
}
