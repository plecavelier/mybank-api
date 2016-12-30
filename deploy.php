<?php
namespace Deployer;
use Symfony\Component\Yaml\Yaml;
require 'recipe/symfony3.php';

// Configuration

set('repository', 'git@github.com:plecavelier/mybank-api.git');

add('shared_files', ['var/jwt/private.pem', 'var/jwt/public.pem']);
add('shared_dirs', ['var/dump']);

add('writable_dirs', []);

// Servers

serverList('servers.yml');

// Tasks

task('deploy:params', function() {
    $distParamsContent = run('cat {{release_path}}/app/config/parameters.yml.dist');
    $distParams = Yaml::parse($distParamsContent);
    $currentParamsContent = run('cat {{deploy_path}}/shared/app/config/parameters.yml');
    $currentParams = Yaml::parse($currentParamsContent);
    if (!is_array($currentParams) || !isset($currentParams['parameters'])) {
        $currentParams = array('parameters' => array());
    }

    $missingParams = 0;
    foreach ($distParams['parameters'] as $key => $value) {
        if (!array_key_exists($key, $currentParams['parameters'])) {
            $missingParams++;
        }
    }
    if ($missingParams > 0) {
        writeln('Some parameters are missing. Please provide them.');
    }

    $newParams = array('parameters' => array());
    foreach ($distParams['parameters'] as $key => $value) {
        if (array_key_exists($key, $currentParams['parameters'])) {
            $newParams['parameters'][$key] = $currentParams['parameters'][$key];
        } else {
            $response = ask($key.' ('.$value.'):');
            if ($response == '') {
                $newParams['parameters'][$key] = $value;
            } else {
                $newParams['parameters'][$key] = $response;
            }
        }
    }

    $newParamsContent = Yaml::dump($newParams);
    $newParamsContent = str_replace('"', '\"', $newParamsContent);
    run('echo "'.$newParamsContent.'" > {{deploy_path}}/shared/app/config/parameters.yml');
});
after('deploy:shared', 'deploy:params');

task('deploy:jwt_keys', function() {
    $currentParamsContent = run('cat {{deploy_path}}/shared/app/config/parameters.yml');
    $currentParams = Yaml::parse($currentParamsContent);
    $password = $currentParams['parameters']['jwt_key_pass_phrase'];

    $sharedPath = "{{deploy_path}}/shared";
    if (!test("[ -s $sharedPath/var/jwt/private.pem ]")) {
        run("openssl genrsa -passout pass:$password -out $sharedPath/var/jwt/private.pem -aes256 4096");
    }
    if (!test("[ -s $sharedPath/var/jwt/public.pem ]")) {
        run("openssl rsa -passin pass:$password -pubout -in $sharedPath/var/jwt/private.pem -out $sharedPath/var/jwt/public.pem");
    }
});
after('deploy:params', 'deploy:jwt_keys');

task('deploy:mysql_dump', function() {
    $currentParamsContent = run('cat {{deploy_path}}/shared/app/config/parameters.yml');
    $currentParams = Yaml::parse($currentParamsContent);

    $host = $currentParams['parameters']['database_host'];
    $port = $currentParams['parameters']['database_port'] ? $currentParams['database_port'] : 3306;
    $name = $currentParams['parameters']['database_name'];
    $user = $currentParams['parameters']['database_user'];
    $password = $currentParams['parameters']['database_password'];

    $sharedPath = "{{deploy_path}}/shared";
    run("mysqldump -h $host --port $port -u \"$user\" -p\"$password\" $name | gzip > $sharedPath/var/dump/$name-dump-$(date +%Y%m%d%H%M%S).sql.gz");
});
before('deploy:mysql_dump', 'database:migrate');

desc('Restart PHP-FPM service');
task('php-fpm:restart', function () {
    // The user must have rights for restart service
    // /etc/sudoers: username ALL=NOPASSWD:/bin/systemctl restart php-fpm.service
    run('sudo systemctl restart php-fpm.service');
});
after('deploy:symlink', 'php-fpm:restart');

// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');
