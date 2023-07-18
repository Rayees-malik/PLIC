<?php

namespace App\Console\Commands;

use App\Models\ApiUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateApiUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:create-user {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates an API user and token';

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
        DB::beginTransaction();

        $name = $this->argument('name');

        $user = ApiUser::create([
            'name' => $name,
        ]);

        if (! $user) {
            DB::rollback();

            $this->error("Could not create API user {$name}");

            return 1;
        }

        $token = $user->createToken(Str::slug($name) . '-token');

        if (! $token) {
            DB::rollBack();

            $this->error("Could not create API token for {$name}");

            return 1;
        }

        DB::commit();

        $this->info("Created API user <fg=yellow;options=bold>{$name}</> with token <fg=yellow;options=bold>{$token->plainTextToken}</>");

        return 0;
    }
}
