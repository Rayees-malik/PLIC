<?php

namespace App\Traits;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasThumbnails
{
    use InteractsWithMedia;

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(256)
            ->height(256)
            ->sharpen(10)
            ->optimize();

        $this->addMediaConversion('small_thumb')
            ->width(128)
            ->height(128)
            ->sharpen(10)
            ->optimize();

        $this->addMediaConversion('customerlink_original_optimized')
            ->performOnCollections('product')
            ->format(Manipulations::FORMAT_PNG)
            ->optimize();

        $this->addMediaConversion('customerlink_small')
            ->performOnCollections('product')
            ->width(75)
            ->height(75)
            ->format(Manipulations::FORMAT_PNG)
            ->optimize();

        $this->addMediaConversion('customerlink_large')
            ->performOnCollections('product')
            ->width(288)
            ->height(288)
            ->format(Manipulations::FORMAT_PNG)
            ->optimize();
    }
}
