<?php

namespace App\Helpers;

use Imagick;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

class ImageHelper
{
    public static function resizeImage($imagePath, $width, $height, $convertToPng = false)
    {
        $path = realpath($imagePath);

        if ($path) {
            $image = new Imagick($path);
            if ($convertToPng) {
                $image->setImageFormat('png');
            }

            $image->scaleImage($width, $height, true);

            $tempname = tempnam(sys_get_temp_dir(), 'plic_');
            $image->writeImage($tempname);

            return $tempname;
        }
    }

    public static function optimizePng($imagePath)
    {
        $path = realpath($imagePath);

        if ($path) {
            $tempname = tempnam(sys_get_temp_dir(), 'plic_');
            Image::load($imagePath)->optimize()->format(Manipulations::FORMAT_PNG)->save($tempname);

            return $tempname;
        }
    }
}
