<?php

namespace App\Console\Commands;

use App\Models\Vendor;
use Illuminate\Console\Command;

class ExportVendorsWithAddresses extends Command
{
    protected $signature = 'export:vendors-with-addresses';
    protected $description = 'Export all vendors that have at least 1 address';

    public function handle()
    {
        $vendors = Vendor::with('address')->whereHas('address')->get();

        $data = [];
        foreach ($vendors as $vendor) {
            $data[] = [
                'name' => $vendor->name,
                'address' => $vendor->address->longFormat(),
            ];
        }

        $filename = 'vendors-with-addresses-' . now()->format('Y-m-d') . '.csv';
        $file = fopen($filename, 'w');
        fputcsv($file, ['Name', 'Address']);
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
        fclose($file);

        $this->info("Exported vendors with addresses to {$filename}");
    }
}
