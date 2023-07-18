<?php

namespace App\Console\Commands;

use App\Actions\Database\RemoveDiscontinuedProductPromosAction;
use Illuminate\Console\Command;

class RemoveDiscontinuedProductPromosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:remove-disco-product-promos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove future promos for discontinued products';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $action = app()->make(RemoveDiscontinuedProductPromosAction::class);
        $action->execute();

        return 0;
    }
}
