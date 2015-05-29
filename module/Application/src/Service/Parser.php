<?php

namespace Application\Service;

use Doctrine\ORM\EntityManagerInterface;
use PHPHtmlParser\Dom;
use Application\Exception\ParserException;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerInterface;
use Zend\Log\Logger;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterAwareInterface;

class Parser implements ParserInterface, LoggerAwareInterface, InputFilterAwareInterface  {
    
    protected $em;
    private $dom;
    
    private $baseUrl;
    private $listUrl;
    private $logger;
    private $inputFilter;
    
    const PARSER_ENTITY = 'Application\Entity\Event';
    
    public function __construct(EntityManagerInterface $em, Dom $dom){
        $this->em = $em;
        $this->dom = $dom;
    }
    
    public function setBaseUrl($baseUrl){
        $this->baseUrl = $baseUrl;
        return $this;
    }
    
    public function setListUrl($listUrl){
        $this->listUrl = $listUrl;
        return $this;
    }
    
    public function setLogger(LoggerInterface $logger){
        $this->logger = $logger;
        return $this;
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter){
        $this->inputFilter = $inputFilter;
        return $this;
    }

    public function getInputFilter(){
        return $this->inputFilter;
    }
    
    public function parse(){
        $this->truncateTable();
        foreach($this->getUrlList() as $url){
            $fullUrlDecoded = $this->prepareUrl($url);
            $this->parseDetail($fullUrlDecoded);
        }
    }
    
    private function parseDetail($url){
        $this->logger->log(Logger::INFO, "Parsing url: ".$url);
        try{
            $this->dom->load($url);
            // The Map - core of whole parser
            $detailContent = $this->dom->find('body .site_wrap #site_content .content_subpage .detail .part_head');
            $event = array(
                "img_big_url" => $this->prepareUrl(trim($detailContent->find('.left_side .image a')->getAttribute('href'))),
                "img_url" => $this->prepareUrl(trim($detailContent->find('.left_side .image a img')->getAttribute('src'))),
                "title" => trim($detailContent->find('.right_side .main .title h1')->text),
                "email" => trim($detailContent->find('.left_side .contact .left_s .email')->text),
                "url_web" => $this->prepareUrl(trim($detailContent->find('.left_side .contact .left_s .web a')->getAttribute('href'))),
                "phone" => trim($detailContent->find('.left_side .contact .right_s .phone')->text),
                "map_iframe_url" => $this->prepareUrl(trim($detailContent->find('.left_side .map iframe')->getAttribute('src'))),
                "place_url_detail" => $this->prepareUrl(trim($detailContent->find('.right_side .main .title .info .item')[0]->find('span a')->getAttribute('href'))),
                "place" => trim($detailContent->find('.right_side .main .title .info .item')[0]->find('span a')->text),
                "entry_amount" => (int) filter_var(trim($detailContent->find('.right_side .main .title .info .item')[2]->find('span')->text), FILTER_SANITIZE_NUMBER_INT),
                "description" => trim($detailContent->find('.right_side .main .desc')->innerHtml),
                "drink_list_url" => $this->prepareUrl(trim($detailContent->find('.right_side .main .foot a')->getAttribute('href'))),
                "social_url" => $this->prepareUrl(trim($detailContent->find('.right_side .main .foot .social a')->getAttribute('href')))
            );
            
            $datetime = explode("<br />", trim($detailContent->find('.right_side .main .title .info .item')[1]->find('span')->innerHtml)); // date + time
            $address = explode("<br />", trim($detailContent->find('.left_side .contact .left_s .address')->innerHtml)); // street + city
            
            $event["date"] = (isset($datetime[0])) ? \DateTime::createFromFormat('d/m/Y',trim($datetime[0]))->format("Y-m-d") : '';
            $event["time"] = (isset($datetime[1])) ? \DateTime::createFromFormat('H:i', trim($datetime[1]))->format("H:i:s") : '';
            $event["street"] = (isset($address[0])) ? trim($address[0]) : '';
            $event["city"] = (isset($address[0])) ? trim($address[0]) : '';
    
            $this->saveDetail($event);
            
        }catch(\Exception $e){
            $this->logger->log(Logger::ERR, "Error while loading the detail of an event: ".$e->getMessage());
        }
    }
    
    private function saveDetail($event){
        $inputFilter = $this->getInputFilter();
        $inputFilter->setData($event);
        if($inputFilter->isValid()){
            try{
                $eventEntity = $this->em->getRepository(self::PARSER_ENTITY);
                $eventEntity->populate($inputFilter->getValues());
                $this->em->persist($eventEntity);
                $this->em->flush();
            }catch(\Exception $e){
                $this->logger->log(Logger::ERR, "Cannot save data: ".$e->getMessage());
                throw new ParserException('Cannot save data: '.$e->getMessage());
            }
        }else{
            $this->logger->log(Logger::WARN, "Invalid data: ".$e->getMessage());
            throw new ParserException('Invalid data: '.$e->getMessage());
        }
    }
    
    public function truncateTable(){
        $this->logger->log(Logger::INFO, "Truncating events table...");
        $cmd = $this->em->getClassMetadata(self::PARSER_ENTITY);
        $connection = $this->em->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->beginTransaction();
        try {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $q = $dbPlatform->getTruncateTableSql($cmd->getTableName());
            $connection->executeUpdate($q);
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
        }
        catch (\Exception $e) {
            $this->logger->log(Logger::ALERT, "Can not truncate the events table: ".$e->getMessage());
            $connection->rollback();
            throw new ParserException('Can not truncate the events table: '.$e->getMessage());
        }
    }
    
    private function getUrlList(){
        $urls = array();
        $this->logger->log(Logger::INFO, "Loading list of events from url: ".$this->listUrl);
        try{
            $this->dom->load($this->listUrl);
            $items = $this->dom->find('.content div .clubs_list .item .texts .column_left h2 a'); // The Detail URL selector
        }catch(\Exception $e){
            $this->logger->log(Logger::ALERT, "Error while loading the list of events: ".$e->getMessage());
            throw new ParserException("Error while loading the list of events: ".$e->getMessage());
        }
        foreach($items as $item){
            $urls[] = $item->getAttribute('href');
        }
        return $urls;
    }
    
    private function prepareUrl($url){
        $decodedUrl = (!empty($url)) ? html_entity_decode(rawurldecode($url)) : $url;
        if (!empty($decodedUrl) && is_string($decodedUrl) && (strlen($decodedUrl) > 0) && substr($decodedUrl, 0, 1) === '/'){
            return $this->baseUrl.$decodedUrl;
        }else{
            return $decodedUrl;
        }
    }
    
}

