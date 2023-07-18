<?php

namespace App\Helpers;

use Illuminate\Support\Arr;
use ZipArchive;

class ZipHelper
{
    public static function zipFiles($files, $customNames = false)
    {
        $zipfile = tempnam(sys_get_temp_dir(), 'plic_');
        $zip = new ZipArchive;
        $zip->open($zipfile, ZipArchive::OVERWRITE);

        $files = Arr::wrap($files);
        foreach ($files as $customName => $file) {
            $zip->addFile($file, $customNames ? $customName : basename($file));
        }
        $zip->close();

        return $zipfile;
    }

    public static function download($zipfile, $filename)
    {
        return response()->download($zipfile, $filename, ['Content-Type: application/octet-stream', 'Content-Length: ' . filesize($zipfile)])->deleteFileAfterSend(true);
    }
}
