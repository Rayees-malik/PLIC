<?php

namespace App;

use Altek\Accountant\Recordable;
use Altek\Eventually\Eventually;
use App\Contracts\RecordableInterface;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia implements RecordableInterface
{
    use Recordable;
    use Eventually;
    use Cloneable;
    use SoftDeletes;

    public $changeTypeOverride = 'media';

    protected $recordableEvents = [
        'created',
        'updated',
        'restored',
        'deleted',
        'forceDeleted',
    ];

    public function onCloning($src, $child = null)
    {
        // Save original id (like RecordableModel)
        $this->cloned_from_id = $src->id;

        // Generate a new uuid
        $this->uuid = Str::uuid();
    }

    public function getFileIcon()
    {
        $iconFile = Arr::get(Config::get('file-icons'), pathinfo($this->file_name, PATHINFO_EXTENSION), Config::get('file-icons')['default']);

        return asset("images/file_icons/{$iconFile}");
    }

    public function getDownloadLink($linkText = null, $linkWrap = null, $title = null, $deleteRoute = null)
    {
        $img = $this->getFileIcon();
        $linkText ??= $this->name;
        if ($linkWrap) {
            $linkText = "<{$linkWrap}>{$linkText}</{$linkWrap}>";
        }

        $deleteHtml = '';
        if ($deleteRoute) {
            $deleteUrl = route($deleteRoute, $this->id);
            $deleteHtml = "<button type=\"button\" class=\"link-btn delete\" data-toggle=\"modal\" title=\"Delete file\" data-action=\"{$deleteUrl}\" data-label=\"{$this->name}\" data-target=\"#deleteModal\">x</button>";
        }

        $title ??= 'Download';

        return "<a href=\"{$this->getUrl()}\" title=\"{$title}\" download=\"\" target=\"_blank\"><img src=\"{$img}\">{$linkText}</a>{$deleteHtml}";
    }
}
