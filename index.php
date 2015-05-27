<?php
define("BASE_PATH", getcwd());
require_once __DIR__ . '/vendor/autoload.php';

$baseConfig = array(
    'modules' => array(
        'Application',
        'DoctrineModule',
        'DoctrineORMModule'
    ),
    'module_listener_options' => array(
        'module_paths' => array(
            './module',
            './vendor'
        ),
        'config_glob_paths' => array('config/{,*.}{global,local}.php')
    )
);

Zend\Mvc\Application::init($baseConfig)->run();
