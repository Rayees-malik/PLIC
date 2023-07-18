<?php

namespace App\Helpers;

class VersionHelper
{
    public static function print()
    {
        $gitBasePath = base_path() . '/.git';

        $gitStr = file_get_contents($gitBasePath . '/HEAD');
        $gitBranchName = rtrim(preg_replace("/(.*?\/){2}/", '', $gitStr));
        $gitPathBranch = $gitBasePath . '/refs/heads/' . $gitBranchName;
        $gitHash = substr(trim(file_get_contents($gitPathBranch)), -12);
        $gitDate = date('F j, Y, g:ia', filemtime($gitPathBranch));

        echo 'Version: ' . $gitDate . '<br>Commit: ' . $gitHash;
    }
}
