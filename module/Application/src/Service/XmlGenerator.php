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
    
    private function tokenTruncate($html, $your_desired_width) {
        $string = strip_tags($html);
        $parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
        $parts_count = count($parts);
        $length = 0;
        $last_part = 0;
        $last_taken = 0;
        foreach($parts as $part){
            $length += strlen($part);
            if ( $length > $your_desired_width ){
                break;
            }
            ++$last_part;
            if ( $part[strlen($part)-1] == '.' ){
                $last_taken = $last_part;
            }
        }
        return implode(array_slice($parts, 0, $last_taken));
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
                $this->logger->log(Logger::INFO, "Generate ARTICLE_ITEM for url: " . $entity->getUrl());
                $perex = $this->tokenTruncate($entity->getDescription(), 255);
                $writer->startElement('ARTICLE_ITEM');
                    $writer->writeElement('ID', $entity->getId());
                    $writer->writeElement('PEREX', $perex);
                    $writer->writeElement('PREVIEW', $perex);
                    $writer->writeElement('TITLE', !empty($entity->getTitle()) ? $entity->getTitle() : "");
                    $writer->startElement('CONTENT');
                        $writer->writeCData(!empty($entity->getDescription()) ? $entity->getDescription() : "");
                    $writer->endElement();
                    $writer->writeElement('IMGURL', !empty($entity->getImgUrl()) ? $entity->getImgUrl() : "");
                    $writer->writeElement('DATE_DISPLAY_FROM', date('d-m-Y H:i:s'));
                    $writer->writeElement('DATE_DISPLAY_TO', !empty($entity->getDate()) ? $entity->getDate()->format('d-m-Y')." 23:59:59" : "");
                    $writer->startElement('EVENT');
                        $datetimeEvent = !empty($entity->getDate()) ? ($entity->getDate()->format('d-m-Y') . " ". (!empty($entity->getTime()) ? $entity->getTime()->format('H:i:s') : "00:00:00" )) : "";
                        $writer->writeElement('DATE_FROM', $datetimeEvent);
                        $writer->writeElement('DATE_TO', !empty($entity->getDate()) ? $entity->getDate()->format('d-m-Y')." 23:59:59" : "");
                        $writer->writeElement('PRICE_INFO', (!empty($entity->getEntryAmount()) ? $entity->getEntryAmount() : "0") . " Kč");
                        $writer->writeElement('URL', !empty($entity->getUrlWeb()) ? $entity->getUrlWeb() : "");
                        $address = (!empty($entity->getStreet()) ? $entity->getStreet() . ", " : "") . (!empty($entity->getCity()) ? $entity->getCity() : "");
                        $writer->writeElement('ADDRESS', $address);
                    $writer->endElement();
                    $writer->startElement('PHOTOGALLERY');
                        $writer->writeElement('IMGURL', !empty($entity->getImgBigUrl()) ? $entity->getImgBigUrl() : "");
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

