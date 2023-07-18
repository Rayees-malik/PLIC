<?php

namespace App\Helpers;

use Illuminate\Support\Arr;

class FileUploadHelper
{
    public static function storeFiles($formData, $keys, $model)
    {
        foreach ($keys as $collection => $key) {
            $files = array_filter(Arr::get($formData, $key, []));
            foreach ($files as $file) {
                $model->addMedia($file)->withCustomProperties(['multiple_formats' => 1])->toMediaCollection($collection);
            }
        }
    }
}
