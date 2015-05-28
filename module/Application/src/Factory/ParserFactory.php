<?php

namespace Application\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Service\Parser;

class ParserFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $em = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $dom = $serviceLocator->get('PHPHtmlParser\Dom');
        $config = $serviceLocator->get('Config');
        $logger = $serviceLocator->get('Application\Logger');
        
        $parser = new Parser($em, $dom);
        $parser->setBaseUrl($config['sources']['base_url']);
        $parser->setListUrl($config['sources']['list_url']);
        $parser->setLogger($logger);
        
        return $parser;
    }
  
}

