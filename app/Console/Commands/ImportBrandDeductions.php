<?php

namespace App\Console\Commands;

use App\Models\Brand;
use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ImportBrandDeductions extends Command
{
    protected $signature = 'import:deductions';

    protected $description = 'Import brand deduction files from the filesystem';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $path = config('plic.brand_deductions.import_path');

        $rdi = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::KEY_AS_PATHNAME);
        foreach (new RecursiveIteratorIterator($rdi, RecursiveIteratorIterator::SELF_FIRST) as $file => $info) {
            $filedata = explode('-', strtoupper(pathinfo($file, PATHINFO_FILENAME)));
            if (count($filedata) >= 3) {
                $fileType = $filedata[1];
                if (! in_array($fileType, ['IN', 'DM', 'CR'])) {
                    continue;
                }

                $brandNumber = substr($filedata[0], 1);
                if (strlen($brandNumber) < 3) {
                    continue;
                }

                $identifier = $filedata[2];

                if ($fileType == 'CR' && $identifier == '') {
                    continue;
                }

                $imported = false;
                $brands = Brand::where('brand_number', 'like', "%{$brandNumber}")->select('id', 'name')->get();
                foreach ($brands as $brand) {
                    $mediaCollection = strtolower("deductions_{$fileType}");

                    echo "Importing {$file} to {$brand->name}.\n";
                    $brand->addMedia("{$file}")
                        ->withCustomProperties(['identifier' => $identifier])
                        ->preservingOriginal()
                        ->toMediaCollection($mediaCollection);

                    $imported = true;
                }

                // Delete file
                if ($imported) {
                    unlink("{$file}");
                }
            }
        }
    }
}
