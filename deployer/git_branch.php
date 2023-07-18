<?php

namespace Deployer;

desc('Generate BRANCH file with Git branch');
task('git:branch', function () {
    if (currentHost()->get('labels')['stage'] == 'production') {
        return;
    }

    $branch = escapeshellarg(run('cd {{release_path}} && git branch --show-current'));
    run("echo {$branch} > {{release_path}}/BRANCH");
})->once();
