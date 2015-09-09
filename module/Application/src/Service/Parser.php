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
    
    private $debugMode = false;
    
    const ENTITY = 'Application\Entity\Event';
    
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
    
    public function setDebugMode($debugMode = true){
        $this->debugMode = $debugMode;
        return $this;
    }

    public function getInputFilter(){
        return $this->inputFilter;
    }
    
    public function parse(){
        $this->emptyTable();
        $result = new \stdClass();
        $result->ok = 0;
        $result->fail = 0;
        foreach($this->getUrlList() as $url){
            $fullUrlDecoded = $this->prepareUrl($url);
            if($this->parseDetail($fullUrlDecoded)){
                $result->ok++;
            }else{
                $result->fail++;
            }
        }
        return $result;
    }
    
    private function parseDetail($url){
        $this->logger->log(Logger::INFO, "Parsing url: ".$url);
        try{
            $this->dom->load($url);
            // The Map - the core of whole parser
            $detailContent = $this->dom->find('body .site_wrap #site_content .content_subpage .detail .part_head');
            $event = array(
                "id" => md5(trim($url)),
                "url" => trim($url),
                "img_big_url" => $this->prepareUrl($this->escapeString($detailContent->find('.left_side .image a')->getAttribute('href'))),
                "img_url" => $this->prepareUrl($this->escapeString($detailContent->find('.left_side .image a img')->getAttribute('src'))),
                "title" => $this->escapeString($detailContent->find('.right_side .main .title h1')->text),
                "email" => $this->escapeString($detailContent->find('.left_side .contact .left_s .email')->text),
                "url_web" => $this->prepareUrl($this->escapeString($detailContent->find('.left_side .contact .left_s .web a')->getAttribute('href'))),
                "phone" => $this->escapeString($detailContent->find('.left_side .contact .right_s .phone')->text),
                "map_iframe_url" => $this->prepareUrl($this->escapeString($detailContent->find('.left_side .map iframe')->getAttribute('src'))),
                "place_url_detail" => $this->prepareUrl($this->escapeString($detailContent->find('.right_side .main .title .info .item')[0]->find('span a')->getAttribute('href'))),
                "place" => $this->escapeString($detailContent->find('.right_side .main .title .info .item')[0]->find('span a')->text),
                "entry_amount" => (int) filter_var($this->escapeString($detailContent->find('.right_side .main .title .info .item')[2]->find('span')->text), FILTER_SANITIZE_NUMBER_INT),
                "description" => $this->escapeString($detailContent->find('.right_side .main .desc')->innerHtml),
                "drink_list_url" => $this->prepareUrl($this->escapeString($detailContent->find('.right_side .main .foot a')->getAttribute('href'))),
                "social_url" => $this->prepareUrl($this->escapeString($detailContent->find('.right_side .main .foot .social a')->getAttribute('href')))
            );
            
            $datetime = preg_split('/(<br[^>]*>){1,2}/i', $this->escapeString($detailContent->find('.right_side .main .title .info .item')[1]->find('span')->innerHtml)); // date + time
            $address = preg_split('/(<br[^>]*>){1,2}/i', $this->escapeString($detailContent->find('.left_side .contact .left_s .address')->innerHtml)); // street + city
            
            $event["date"] = (isset($datetime[0])) ? \DateTime::createFromFormat('d/m/Y',trim($datetime[0]))->format("Y-m-d") : '';
            $event["time"] = (isset($datetime[1])) ? \DateTime::createFromFormat('H:i', trim($datetime[1]))->format("H:i:s") : '';
            $event["street"] = (isset($address[0])) ? trim($address[0]) : '';
            $event["city"] = (isset($address[1])) ? trim($address[1]) : '';
    
            $this->saveDetail($event);
            
            return true;
        }catch(\Exception $e){
            $this->logger->log(Logger::ERR, "Error while loading / parsing the detail of an event: ".$e->getMessage());
            return false;
        }
    }
    
    private function saveDetail($event){
        $inputFilter = $this->getInputFilter();
        $inputFilter->setData($event);
        if($inputFilter->isValid()){
            try{
                //@TODO find better way howto return new instance of the entity
                $entityName = self::ENTITY;
                $eventEntity = new $entityName; //$this->em->getRepository(self::ENTITY);
                $values = $inputFilter->getValues();
                $eventEntity->populate($values);
                $this->em->persist($eventEntity);
                $this->em->flush();
            }catch(\Exception $e){
                $this->logger->log(Logger::ERR, "Cannot save data: ".$e->getMessage());
                throw new ParserException('Cannot save data: '.$e->getMessage());
            }
        }else{
            $this->logger->log(Logger::WARN, "Invalid entity data");
            throw new ParserException("Invalid entity data");
        }
    }
    
    public function emptyTable(){
        $this->logger->log(Logger::INFO, "Truncating events table...");
        $cmd = $this->em->getClassMetadata(self::ENTITY);
        $connection = $this->em->getConnection();
        $connection->beginTransaction();
        try {
            //$connection->query('SET FOREIGN_KEY_CHECKS=0');
            $connection->query('DELETE FROM '.$cmd->getTableName());
            //$connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
        }
        catch (\Exception $e) {
            $this->logger->log(Logger::EMERG, "Can not empty the events table: ".$e->getMessage());
            $connection->rollback();
            throw new ParserException('Can not empty the events table: '.$e->getMessage());
        }
    }
    
    private function getUrlList(){
        if($this->debugMode){ 
            return array(
                __DIR__."/../../../../data/source-examples/event-001.html",
                __DIR__."/../../../../data/source-examples/event-002.html"
            );
        }
        $urls = array();
        $this->logger->log(Logger::INFO, "Loading list of events from url: ".$this->listUrl);
        try{
            $this->dom->load($this->listUrl);
            $items = $this->dom->find('.content div .clubs_list .item .texts .column_left h2 a'); // The Detail URL selector
        }catch(\Exception $e){
            $this->logger->log(Logger::EMERG, "Error while loading the list of events: ".$e->getMessage());
            throw new ParserException("Error while loading the list of events: ".$e->getMessage());
        }
        foreach($items as $item){
            $urls[] = $item->getAttribute('href');
        }
        return $urls;
    }
    
    private function prepareUrl($url){
        $decodedUrl = (!empty($url)) ? html_entity_decode(rawurldecode($url)) : $url;
        if (!empty($decodedUrl) && is_string($decodedUrl) && (strlen($decodedUrl) > 0) && substr($decodedUrl, 0, 1) === '/' && !file_exists($url)){
            return $this->baseUrl.$decodedUrl;
        }else{
            return $decodedUrl;
        }
    }
    
    private function escapeString($string){
        return iconv("UTF-8", "UTF-8//IGNORE", trim($string));
    }
    
}

