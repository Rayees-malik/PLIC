<?php

namespace App\Traits\BladeHelpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

trait UploaderHelpers
{
    public static function includeUploaders($types)
    {
        $types = Arr::wrap($types);

        $js = file_get_contents(dirname(__FILE__) . '/uploader-config/loader.html', false);

        $js .= '<script type="text/javascript" src="' . mix('js/jquery.fileuploader.js') . "\"></script>\n";
        foreach ($types as $type) {
            $js .= '<script type="text/javascript" src="' . mix("js/uploaders/{$type}.js") . "\"></script>\n";
        }

        return $js;
    }

    public static function uploaderModelFields($model)
    {
        if ($model && $model->id) {
            $template = file_get_contents(dirname(__FILE__) . '/uploader-config/model-fields.html', false);

            return str_replace('$modelId', $model->id, str_replace('$modelClass', get_class($model), $template));
        }
    }

    public static function restoreUploaderField($model, $collection, $type)
    {
        $deletedMedia = $model->media()->onlyTrashed()->where('collection_name', $collection)->get();
        if ($deletedMedia->count() > 0) {
            return view('partials.helpers.restore-media')->with(compact('deletedMedia', 'type'))->render();
        }

        return '';
    }

    public static function uploaderField($model, $collection, $settings = [])
    {
        $type = Arr::get($settings, 'type', 'thumbnail');
        $limit = Arr::get($settings, 'limit', '1');
        $extensions = Config::get('uploader-extensions')[Arr::get($settings, 'extensions', 'image')];
        $allowRestore = Arr::get($settings, 'allowRestore', false);
        $customProperties = Arr::wrap(Arr::get($settings, 'customProperties', []));
        $enabled = Arr::wrap(Arr::get($settings, 'enabled', false)) || ($model && $model->id);

        $template = file_get_contents(dirname(__FILE__) . '/uploader-config/input.html', false);
        $template = str_replace('$collection', $collection, $template);
        $template = str_replace('$type', $type, $template);
        $template = str_replace('$limit', $limit, $template);
        $template = str_replace('$extensions', $extensions, $template);

        if ($enabled) {
            if ($model && $model->id) {
                $template = str_replace('$config', static::getImagePreloadData($model->getMedia($collection), $customProperties), $template);
            } else {
                $template = str_replace('$config', '', $template);
            }
        } else {
            $template = str_replace('$config', 'disabled', $template);
        }

        if ($allowRestore) {
            $template .= self::restoreUploaderField($model, $collection, $type);
        }

        return $template;
    }

    public static function getImagePreloadData($collection, $customProperties = [])
    {
        $collectionData = [];
        foreach ($collection as $media) {
            $data = [
                'name' => $media->file_name,
                'size' => $media->size,
                'type' => $media->mime_type,
                'file' => $media->getUrl(),
                'data' => [
                    'file_id' => $media->id,
                ],
            ];

            foreach ($customProperties as $property) {
                if ($media->hasCustomProperty($property)) {
                    $data['data'][$property] = $media->getCustomProperty($property);
                }
            }
            if ($media->hasGeneratedConversion('thumb')) {
                $data['data']['thumbnail'] = $media->getUrl('thumb');
            }

            $collectionData[] = $data;
        }

        if (empty($collectionData)) {
            return '';
        }

        $json = htmlspecialchars(json_encode($collectionData, JSON_UNESCAPED_SLASHES));

        return "data-preloaded=\"{$json}\"";
    }
}
