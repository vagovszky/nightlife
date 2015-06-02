<?php

namespace Application;

use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;

class Module implements ConfigProviderInterface, ConsoleUsageProviderInterface, ConsoleBannerProviderInterface {

    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getConsoleBanner(Console $console) {
        return
            "=====================================================================\n" .
            "=        The Night Life Parser        |        version 0.0.1        =\n" .
            "=====================================================================\n" ;
    }

    public function getConsoleUsage(Console $console) {
        return array(
            "parse" => "Save to database",
            "xml"   => "Generate XML"
        );
    }
}