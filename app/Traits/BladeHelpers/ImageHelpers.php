<?php

namespace App\Traits\BladeHelpers;

trait ImageHelpers
{
    public static function showFirstImage($model, $collection, $size)
    {
        $media = $model->getMedia($collection)->first();
        if ($media) {
            return view('partials.helpers.image')->with(['media' => $media, 'size' => $size])->render();
        }

        return view('partials.helpers.default-image')->with(['size' => $size])->render();
    }

    public static function showMedia($media, $size)
    {
        return view('partials.helpers.image')->with(['media' => $media, 'size' => $size])->render();
    }
}
