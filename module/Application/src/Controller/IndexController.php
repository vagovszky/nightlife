<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;
use Zend\Console\ColorInterface as Color;

class IndexController extends AbstractActionController {

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