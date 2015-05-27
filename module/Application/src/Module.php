<?php

namespace Application;

use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;

class Module implements ConfigProviderInterface, ConsoleUsageProviderInterface, ConsoleBannerProviderInterface, DependencyIndicatorInterface {

    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getConsoleBanner(Console $console) {
        return
                "==------------------------------------------------------==\n" .
                "                 The Night Life Parser                    \n" .
                "==------------------------------------------------------==\n" ;
    }

    public function getConsoleUsage(Console $console) {
        return array(
            "default" => "Default action",
        );
    }

    public function getModuleDependencies() {
        return array(); //return array('OtherModule');
    }
}