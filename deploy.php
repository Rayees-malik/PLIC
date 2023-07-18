<?php

namespace Deployer;

use Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

require 'recipe/laravel.php';
require 'deployer/task_as400_migrate.php';
require 'deployer/task_clear_storage.php';
require 'deployer/task_import_deductions.php';
require 'deployer/honeybadger.php';
require 'deployer/git_branch.php';
require 'contrib/slack.php';
require 'deployer/restart_workers.php';

with(Dotenv::createImmutable(__DIR__)->load());

// Project name
set('application', 'Purity Life Information Centre');

// Project repository
set('repository', 'git@github.com:ZeusSystems/plic.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

set('update_code_strategy', 'clone');

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);
set('allow_anonymous_stats', false);

set('php_fpm_service', 'php8.1-fpm');
set('default_timeout', 3400);

set('slack_webhook', env('SLACK_DEPLOY_WEBHOOK_URL'));
set('honeybadger_api_key', env('HONEYBADGER_API_KEY'));
set('honeybadger_api_js_key', env('HONEYBADGER_API_JS_KEY'));

// Hosts
host('dev')
    ->set('hostname', '10.50.4.17')
    ->set('labels', ['stage' => 'development'])
    ->set('deploy_path', '/var/www/plic')
    ->set('composer_options', '--verbose --prefer-dist --no-interaction --optimize-autoloader')
    ->set('remote_user', 'purity');

host('dev-2')
    ->set('hostname', '10.50.4.17')
    ->set('labels', ['stage' => 'development'])
    ->set('deploy_path', '/var/www/plic-2')
    ->set('composer_options', '--verbose --prefer-dist --no-interaction --optimize-autoloader')
    ->set('remote_user', 'purity');

host('staging')
    ->set('hostname', '10.50.4.17')
    ->set('labels', ['stage' => 'staging'])
    ->set('deploy_path', '/var/www/plic-staging')
    ->set('composer_options', '--verbose --prefer-dist --no-interaction --optimize-autoloader')
    ->set('remote_user', 'purity');

host('prod')
    ->set('hostname', '10.50.4.51')
    ->set('labels', ['stage' => 'production'])
    ->set('deploy_path', '/var/www/plic')
    ->set('remote_user', 'purity');

// Tasks
task('build', function () {
    if (currentHost()->get('labels')['stage'] === 'production') {
        run('cd {{release_path}} && npm install && npm run prod');
    } else {
        run('cd {{release_path}} && npm install && npm run dev');
    }
});

task('debugbar:clear', function () {
    if (currentHost()->get('labels')['stage'] !== 'production') {
        run('cd {{release_path}} && php artisan debugbar:clear');
    }
});

task('schedule-monitor:sync', function () {
    run('cd {{release_path}} && php artisan schedule-monitor:sync');
});

after('deploy:vendors', 'build');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
after('deploy:failed', 'slack:notify:failure');

after('deploy:info', 'slack:notify');

before('deploy:symlink', 'artisan:migrate');
before('deploy:symlink', 'artisan:cache:clear');
before('deploy:symlink', 'artisan:view:clear');
before('deploy:symlink', 'schedule-monitor:sync');
before('deploy:symlink', 'debugbar:clear');
before('deploy:symlink', 'git:branch');

after('deploy', 'supervisor:reload');
after('deploy', 'honeybadger:notify');
after('deploy', 'slack:notify:success');
