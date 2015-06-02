<?php

namespace Application\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Service\XmlGenerator;


class XmlGeneratorFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $em = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $xmlWriter = $serviceLocator->get('XMLWriter');
        $logger = $serviceLocator->get('Application\Logger');
        
        $xmlGenerator = new XmlGenerator($em, $xmlWriter);
        $xmlGenerator->setLogger($logger);
      
        return $xmlGenerator;
    }
  
}

