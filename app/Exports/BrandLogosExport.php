<?php

namespace App\Exports;

use App\Helpers\StatusHelper;
use App\Helpers\ZipHelper;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandLogosExport
{
    public function export(Request $request)
    {
        $brands = Brand::select('id', 'name', 'brand_number', 'website')
            ->where('status', StatusHelper::ACTIVE)
            ->get()
            ->map(function ($item, $value) {
                $logo = $item->getFirstMedia('logo');

                if (! is_null($logo)) {
                    $newItem = [
                        'id' => $item->id,
                        'name' => $item->name,
                        'brand_number' => $item->brand_number,
                        'website' => $item->website,
                        'logo' => $logo->getPath(),
                        'filename' => Str::slug($item->name) . '_' . Str::uuid() . '.' . Str::afterLast($logo->mime_type, '/'),
                    ];
                } else {
                    $newItem = [
                        'id' => $item->id,
                        'name' => $item->name,
                        'brand_number' => $item->brand_number,
                        'website' => $item->website,
                        'filename' => '',
                    ];
                }

                return $newItem;
            });

        $csv = tempnam(sys_get_temp_dir(), 'brand_logos_');
        $fp = fopen($csv, 'w');
        fputcsv($fp, ['id', 'name', 'brand_number', 'website', 'filename']);

        $brands->map(function ($item) {
            return [
                $item['id'],
                $item['name'],
                $item['brand_number'],
                $item['website'],
                (array_key_exists('logo', $item) && realpath($item['logo'])) ? $item['filename'] : '',
            ];
        })->each(
            fn ($item) => fputcsv($fp, $item)
        );

        fclose($fp);
        rename($csv, $csv . '.csv');

        $brandLogos = $brands
            ->filter(fn ($item) => array_key_exists('logo', $item))
            ->filter(fn ($item) => realpath($item['logo']))
            ->flatMap(fn ($item) => [$item['filename'] => $item['logo']]);

        $brandLogos->put('brand_logos_list.csv', $csv . '.csv');

        $zipFile = ZipHelper::zipFiles($brandLogos->all(), true);

        return ZipHelper::download($zipFile, 'brand_logos.zip');
    }
}
