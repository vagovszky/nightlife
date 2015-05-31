<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
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
        $console->writeLine("Parsing started!");
        
        $result = $this->getServiceLocator()->get('Application\Service\Parser')->parse();
        
        $console->writeLine("Parsing completed!");
        $console->write("Successfully imported: ");
        $console->write($result->ok, Color::GREEN);
        $console->write(", with errors: ");
        $console->write($result->fail, Color::RED);
        $console->write("\n");
    }

}