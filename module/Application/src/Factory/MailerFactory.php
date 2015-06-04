<?php

namespace Application\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Service\Mailer;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;

class MailerFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator) {
        
        $config = $serviceLocator->get('Config');
        $logger = $serviceLocator->get('Application\Logger');
        $transport = new Smtp();
        $transport->setOptions(new SmtpOptions($config['mail']['transport']['options']));
        
        $mailer = new Mailer($transport);
        $mailer->setLogger($logger);
        $mailer->setRecipients($config['mailer']['recipients']);
        
        return $mailer;
    }
  
}

