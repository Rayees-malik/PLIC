<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ClearStorageCommand extends Command
{
    protected $signature = 'storage:clear';

    protected $description = 'Delete all files in the public storage';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $file = new Filesystem;
        $file->cleanDirectory('storage/app/public');
    }
}
