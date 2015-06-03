<?php

namespace Application\Service;

use Doctrine\ORM\EntityManagerInterface;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerInterface;
use Zend\Log\Logger;

class XmlGenerator implements XmlGeneratorInterface, LoggerAwareInterface {
    
    private $em;
    private $xmlWriter;
    private $logger;
    
    const ENTITY = 'Application\Entity\Event';
    
    public function __construct(EntityManagerInterface $em, \XMLWriter $xmlWriter) {
        $this->em = $em;
        $this->xmlWriter = $xmlWriter;
    }
    
    public function setLogger(LoggerInterface $logger){
        $this->logger = $logger;
        return $this;
    }
    
    public function getXmlWriter(){
        return $this->xmlWriter;
    }
    
    private function createPerex($html, $limit = 255, $terminator = '...'){
        $text = strip_tags($html);
        if (mb_strlen($text) <= $limit) {
            return $text;
        } else {
            if(mb_strlen($text) > ($limit - strlen($terminator))){
                $text = mb_substr($text, 0, $limit - strlen($terminator));
                $pos = mb_strrpos($text, ".");
                if($pos === false){
                    $pos = mb_strrpos($text, " ");
                }
                return mb_substr($text, 0, ($pos ? $pos : 0)) . $terminator;
            }else{
                return $text;
            }
        }
    }
    
    public function getXML() {
        try{
            $this->logger->log(Logger::INFO, "Start generating XML...");
            $writer = $this->getXmlWriter();
            $writer->openMemory();
            $writer->setIndent(true);
            $writer->startDocument('1.0','UTF-8');
            $writer->startElement('ARTICLES');
            foreach($this->em->getRepository(self::ENTITY)->findAll() as $entity){
                $title = $entity->getTitle();
                $description = $entity->getDescription();
                $imgurl = $entity->getImgUrl();
                $date = $entity->getDate();
                $time = $entity->getTime();
                $amount = $entity->getEntryAmount();
                $street = $entity->getStreet();
                $city = $entity->getCity();
                $imgBigUrl = $entity->getImgBigUrl();
                
                $this->logger->log(Logger::INFO, "Generate ARTICLE_ITEM for url: " . $entity->getUrl());
                $perex = $this->createPerex($entity->getDescription(), 255);
                $writer->startElement('ARTICLE_ITEM');
                    $writer->writeElement('ID', $entity->getId());
                    $writer->writeElement('PEREX', $perex);
                    $writer->writeElement('PREVIEW', $perex);
                    $writer->writeElement('TITLE', !empty($title) ? $title : "");
                    $writer->startElement('CONTENT');
                        $writer->writeCData(!empty($description) ? $description : "");
                    $writer->endElement();
                    $writer->writeElement('IMGURL', !empty($imgurl) ? $imgurl : "");
                    $writer->writeElement('DATE_DISPLAY_FROM', date('d-m-Y H:i:s'));
                    $writer->writeElement('DATE_DISPLAY_TO', !empty($date) ? $date->format('d-m-Y')." 23:59:59" : "");
                    $writer->startElement('EVENT');
                        $datetimeEvent = !empty($date) ? ($date->format('d-m-Y') . " ". (!empty($time) ? $time->format('H:i:s') : "00:00:00" )) : "";
                        $writer->writeElement('DATE_FROM', $datetimeEvent);
                        $writer->writeElement('DATE_TO', !empty($date) ? $date->format('d-m-Y')." 23:59:59" : "");
                        $writer->writeElement('PRICE_INFO', (!empty($amount) ? $amount : "0") . " Kč");
                        $writer->writeElement('URL', "");
                        $address = (!empty($street) ? $street . ", " : "") . (!empty($city) ? $city : "");
                        $writer->writeElement('ADDRESS', $address);
                    $writer->endElement();
                    $writer->startElement('PHOTOGALLERY');
                        $writer->writeElement('IMGURL', !empty($imgBigUrl) ? $imgBigUrl : "");
                    $writer->endElement();
                $writer->endElement();
            }
            $writer->endElement();
            $this->logger->log(Logger::INFO, "XML done.");
            return $writer->outputMemory();
        }catch(\Exception $e){
            $this->logger->log(Logger::ALERT, "XML generator error: ".$e->getMessage());
            return false;
        }
    }
    
    
}

