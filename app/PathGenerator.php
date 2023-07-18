<?php

namespace App;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;

class PathGenerator extends DefaultPathGenerator
{
    protected function getBasePath(Media $media): string
    {
        // If this is a cloned entry we don't actually have a file on the filesystem
        // so we need to point to the original id that we cloned
        return empty($media->cloned_from_id) ? $media->getKey() : $media->cloned_from_id;
    }
}
