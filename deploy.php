<?php

namespace Deployer;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/deployer/deployer/recipe/symfony3.php';

new \SourceBroker\DeployerExtended\Loader();

set('repository', 'git@github.com:benjaminrau/ingrid-api.git');

set('writable_dirs', array_merge(get('writable_dirs'),['web/uploads','web/media']));
set('shared_files', array_merge(get('shared_files'),['.env']));
set('writable_mode', 'acl');
set('writable_use_sudo', true);

host('beta')
    ->hostname('benjaminrau.com')
    ->port(22)
    ->user('root')
    ->forwardAgent()
    ->stage('live')
    ->set('deploy_path', '/var/www/ingrid-api')
    ->set('db_settings_storage_path', '/var/www/ingrid-api/shared/.deploy/database/.dumps')
    ->set('env', 'prod')
    ->set('composer_options', 'install --verbose --prefer-dist --no-progress --no-interaction -o -a')
    ->set('schema_update', true)
    ->set('clear_paths', ['web/config.php'])
    ->set('http_user','www-data')
    ->set('http_group','www-data')
    ->set('shared_dirs', array_merge(get('shared_dirs'),['web/media/cache','web/uploads/media']))
    ->set('db_databases',
        array_merge(
            get('db_databases')
        ))
    ->set('branch', 'live');

localhost()
    ->stage('local')
    ->set('deploy_path', getcwd())
    ->set('db_settings_storage_path', __DIR__ . '/.deploy/database/.dumps')
    ->set('db_databases',
        array_merge(
            get('db_databases')
        ));

after('deploy:failed', 'deploy:unlock');

before('deploy:symlink', 'database:migrate');
