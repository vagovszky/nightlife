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
    
    public function xmlAction(){
        
        $dir = __DIR__."/../../../../data/xml";
        if(!file_exists($dir)){
            mkdir($dir, 0775, true);
        }
        
        $path = $dir."/events-".date('ymdHis').".xml";
        
        $console = $this->getConsole();
        if ($console instanceof Virtual) {
            return "No console support !!!";
        }
        $console->writeLine("Generating XML ...");
        
        $xml = $this->getServiceLocator()->get('Application\Service\XmlGenerator')->getXml();
        if(!$xml){
            $console->writeLine("XML generator FAILED!", Color::RED);
        }else{
            file_put_contents($path, $xml);
            $console->writeLine("XML generated successfully!", Color::GREEN);
        }
    }
}