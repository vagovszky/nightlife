<?php
$basePath = getcwd();

define("BASE_PATH", $basePath);

// load autoloader
if (file_exists("$basePath/vendor/autoload.php")) {
    require_once "$basePath/vendor/autoload.php";
} elseif (file_exists("$basePath/init_autoload.php")) {
    require_once "$basePath/init_autoload.php";
} elseif (\Phar::running()) {
    require_once __DIR__ . '/vendor/autoload.php';
} else {
    echo 'Error: I cannot find the autoloader of the application.' . PHP_EOL;
    echo "Check if $basePath contains a valid ZF2 application." . PHP_EOL;
    exit(2);
}
if (!class_exists('Zend\Loader\AutoloaderFactory')) {
    throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.');
}

$baseConfig = require(__DIR__ . '/config/application.config.php');

Zend\Mvc\Application::init($baseConfig)->run();
