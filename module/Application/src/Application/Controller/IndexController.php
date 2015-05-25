<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;
use Zend\Console\ColorInterface as Color;

class IndexController extends AbstractActionController {

    protected $em;

    public function setEntityManager(EntityManager $em) {
        $this->em = $em;
    }

    public function getEntityManager() {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }

    public function getConsole() {
        return $this->getServiceLocator()->get('console');
    }
    
    public function defaultAction(){
        $console = $this->getConsole();
        if ($console instanceof Virtual) {
            return "No console support !!!";
        }
        //$console->write("Default action".PHP_EOL);
    }

}