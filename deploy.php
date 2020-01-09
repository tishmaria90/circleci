<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'circletest');

// Project repository
//set('repository', 'git@github.com:tishmaria90/circleci.git');

// [Optional] Allocate tty for git clone. Default value is false.
//set('git_tty', true);

set('keep_releases', 2);

// Shared files/dirs between deploys
add('shared_files', [
    '.env',
]);
add('shared_dirs', [
    'storage',
]);

// Writable dirs by web server
add('writable_dirs', []);


// Hosts
host('2up')
    ->configFile('deployment/ssh_config')
    ->set('deploy_path', '/var/www/{{application}}');

// Tasks
task('upload', function () {
    $skip_upload = [
        '.',
        '..',
        '.env',
        '.env.circleci',
        '.env.example',
        '.git',
        '.gitignore',
        '.gitattributes',
        '.phpunit.result.cache',
        '.editorconfig',
        '.styleci.yml',
        'deploy.php',
        'README.md',

        '.circleci',
        'deployment',
        'vendor',

        'index.html',
    ];

    $to_upload = array_diff(scandir(__DIR__), $skip_upload);

    foreach ($to_upload as $item) {
        upload(__DIR__ . "/" . $item, '{{release_path}}');
    }

});

task('deploy', [
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'upload',
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

