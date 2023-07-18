<?php

namespace Deployer;

desc('Clear public storage');
task('artisan:storage:clear', artisan('storage:clear'));
