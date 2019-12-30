<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'circletest');

// Project repository
set('repository', 'git@github.com:tishmaria90/circleci.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

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
//localhost();
host('2up')
    ->configFile('deployment/ssh_config')
//    ->user('mtish')
//    ->identityFile('deployment/decrypted_credentials/id_rsa_deployment')
//    ->forwardAgent(true)
//    ->multiplexing(true)
//    ->addSshOption('UserKnownHostsFile', '/dev/null')
//    ->addSshOption('StrictHostKeyChecking', 'no')
    ->set('deploy_path', '/var/www/{{application}}');
//inventory('deployment/hosts.yml');
//host('test');

// Tasks
task('test', function () {
    writeln('Hello world');
});
task('pwd', function () {
    $result = run('pwd');
    writeln("Current dir: $result");
});
task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

