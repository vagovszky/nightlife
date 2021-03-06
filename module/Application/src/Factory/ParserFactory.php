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
        $filter = $serviceLocator->get('InputFilterManager')->get('Application\Filter\EventFilter');
        
        $parser = new Parser($em, $dom);
        $parser->setBaseUrl($config['parser']['sources']['baseUrl']);
        $parser->setListUrl($config['parser']['sources']['listUrl']);
        $parser->setDebugMode($config['parser']['debugMode']);
        $parser->setLogger($logger);
        $parser->setInputFilter($filter);
        
        return $parser;
    }
  
}

