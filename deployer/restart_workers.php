<?php

namespace Deployer;

desc('Restart queue workers');
task('supervisor:reload', function () {
    run('cd {{release_path}} && supervisorctl reload');
});
