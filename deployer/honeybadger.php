<?php

namespace Deployer;

use Deployer\Utility\Httpie;

desc('Notifying Honeybadger of deployment');
task('honeybadger:notify', function () {
    $environmentData = [
        'deploy' => [
            'environment' => currentHost()->get('labels')['stage'],
            'revision' => runLocally('git log -n 1 --format="%h"'),
            'local_username' => get('user'),
        ],
    ];

    if (get('honeybadger_api_key')) {
        $phpData = [
            'api_key' => get('honeybadger_api_key'),
        ];

        Httpie::post('https://api.honeybadger.io/v1/deploys')->formBody([...$phpData, ...$environmentData])->send();
    } else {
        echo "Skipping honeybadger PHP notification because no API key is set.\n";
    }

    if (get('honeybadger_api_js_key')) {
        $javascriptData = [
            'api_key' => get('honeybadger_api_js_key'),
        ];

        Httpie::post('https://api.honeybadger.io/v1/deploys')->formBody([...$javascriptData, ...$environmentData])->send();
    } else {
        echo "Skipping honeybadger Javascript notification because no API key is set.\n";
    }
})->once();
