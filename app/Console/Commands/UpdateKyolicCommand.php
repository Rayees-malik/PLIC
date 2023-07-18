<?php

namespace App\Console\Commands;

use App\Contracts\Geocoding\GeocodingGateway;
use App\Models\AS400\AS400ZeusRetailer;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateKyolicCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:kyolic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Kyolic';

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
    public function handle(GeocodingGateway $geocodingGateway)
    {
        $retailers = AS400ZeusRetailer::select([
            'name',
            'address',
            'city',
            'province',
            'postal_code',
            'contact_email',
            'contact_phone',
        ])->where('category', 'KYOL')
            ->distinct()
            ->get();

        $numberRetailers = $retailers->count();

        if ($numberRetailers == 0) {
            $this->info('No retailers found.');

            return 0;
        }

        $this->info(sprintf('Found %d retailers.', $numberRetailers));
        $this->comment('Starting geocoding...');
        $bar = $this->output->createProgressBar($numberRetailers);
        $bar->start();

        $retailers = $retailers->map(function ($retailer, $key) use ($geocodingGateway, $bar) {
            $updated_on = now();

            try {
                $coordinates = $geocodingGateway->getLatitudeAndLongitude(
                    $retailer->address . ', ' . $retailer->city . ', ' . $retailer->province . ', ' . $retailer->postal_code
                );

                $bar->advance();

                return [
                    'title' => $retailer->name,
                    'street' => $retailer->address,
                    'city' => $retailer->city,
                    'state' => $retailer->province,
                    'postal_code' => $retailer->postal_code,
                    'country' => 38,
                    'email' => $retailer->contact_email,
                    'phone' => $retailer->contact_phone,
                    'lat' => $coordinates->latitude,
                    'lng' => $coordinates->longitude,
                    'logo_id' => 2,
                    'updated_on' => $updated_on,
                ];
            } catch (Exception $e) {
                $this->error($e->getMessage());

                return null;
            }
        })->reject(fn ($item) => is_null($item))
            ->toArray();

        $this->info('Finished geocoding!');

        $this->comment('Truncating table...');
        DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->truncate();
        $this->info('Truncated table!');

        $this->comment('Inserting new data...');
        DB::connection('kyolic')->table('wp_whwwupoupw_asl_stores')->insert($retailers);
        $this->info('Inserted new data!');

        $this->info('Kyolic update complete!');

        return 0;
    }
}
