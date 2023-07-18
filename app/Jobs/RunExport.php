<?php

namespace App\Jobs;

use App\Contracts\Exports\Exportable;
use App\Notifications\Exports\ExportComplete;
use App\Notifications\Exports\ExportFailed;
use App\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class RunExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private readonly Exportable $export, private readonly User $user)
    {
        $this->onQueue('exports');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $zipFile = $this->export->export();

        if (! file_exists($zipFile)) {
            report(new Exception('No images found for the selected criteria!'));
            $this->user->notify(new ExportFailed('No images found for the selected criteria!'));

            return;
        }

        $this->user->notify(new ExportComplete(URL::temporarySignedRoute('exports.download', now()->addMinutes(5), ['file' => $zipFile, 'filename' => $this->export->getFilename()])));
    }
}
