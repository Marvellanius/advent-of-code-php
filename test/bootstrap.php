<?php

define('TEST_PATH', __DIR__);
define('UNIT_TEST_PATH', TEST_PATH . '/unit');

echo UNIT_TEST_PATH;

error_reporting(E_ALL);

date_default_timezone_set('Europe/Amsterdam');

setup_temp_dir_for_symfony_cache();

$loader = require TEST_PATH . '/../vendor/autoload.php';

// Add test dirs in case other tests load each other
$loader->add('', UNIT_TEST_PATH);

function setup_temp_dir_for_symfony_cache()
{
    // use our own temp directory, and clean it up afterwards
    $tempDir = is_dir('/run/shm/') ? '/run/shm/phpunit-tmp' . getmypid() : '/tmp/phpunit-tmp' . getmypid();
    @mkdir($tempDir);

    // make php aware of our new directory
    putenv('TMPDIR=' . $tempDir);
    ini_set('sys_temp_dir', $tempDir);

    // cleanup after shutdown
    register_shutdown_function(function () use ($tempDir) {
        if (is_dir($tempDir)) {
            exec('rm -r ' . escapeshellarg($tempDir));
        }
    });
}
