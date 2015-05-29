<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\EntityManager;
use Zend\Console\ColorInterface as Color;

class IndexController extends AbstractActionController {

    public function getConsole() {
        return $this->getServiceLocator()->get('console');
    }
    
    public function parseAction(){
        $console = $this->getConsole();
        if ($console instanceof Virtual) {
            return "No console support !!!";
        }
        
        /*
        $data = array();
        $data["title"] = "CMYK";
        $parser = $this->getServiceLocator()->get('Application\Service\Parser');
        $filter = $parser->getInputFilter();
        $filter->setData($data);
        var_dump($filter->isValid());
        */
        $this->getServiceLocator()->get('Application\Service\Parser')->parse();
    }

}