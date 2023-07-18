<?php

namespace App\Models\AS400;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class AS400Margin extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'as400_margins';

    public static function migrate($brands = null)
    {
        if (! $brands) {
            $brands = Brand::select('id', 'brand_number')->get();
        }
        $brands = array_column($brands->toArray(), 'brand_number', 'id');

        $rows = DB::connection('as400')
            ->table('glmast')
            ->where([
                'glm_company_no' => 'P',
                'glm_status' => 'A',
            ])
            ->where(DB::raw('left(glm_account_no, 1)'), '5')
            ->where(DB::raw('right(glm_account_no, 2)'), '00')
            ->select(['glm_account_no', 'glm_ref_a_2'])
            ->get();

        $records = [];
        foreach ($rows as $row) {
            $margin = trim($row->glm_ref_a_2);
            if (empty($margin)) {
                continue;
            }

            $glBrand = substr($row->glm_account_no, 1, 3);
            $matchedBrands = array_filter($brands, function ($brandNumber) use ($glBrand) {
                return substr(preg_replace('/\D/', '', $brandNumber), -3) == $glBrand;
            });

            if (! $matchedBrands) {
                continue;
            }

            foreach (array_keys($matchedBrands) as $brandId) {
                $record = [];
                $record['margin'] = $row->glm_ref_a_2;
                $record['brand_id'] = $brandId;

                $records[] = $record;
            }
        }

        AS400Margin::truncate();
        foreach (array_chunk($records, 1000) as $chunk) {
            AS400Margin::insert($chunk);
        }
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Brand::class);
    }
}
