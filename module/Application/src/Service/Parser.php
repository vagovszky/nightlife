<?php

namespace Application\Service;

use Doctrine\ORM\EntityManagerInterface;
use PHPHtmlParser\Dom;
use Application\Exception\ParserException;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerInterface;
use Zend\Log\Logger;

class Parser implements ParserInterface, LoggerAwareInterface  {
    
    protected $em;
    private $dom;
    
    private $baseUrl;
    private $listUrl;
    private $logger;
    
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
            $detailContent = $this->dom->find('body .site_wrap #site_content .content_subpage .detail .part_head');
                        
            // ---------------------------
            $event = new \stdClass;
            $event->img_big_url = $this->prepareUrl(trim($detailContent->find('.left_side .image a')->getAttribute('href')));
            $event->img_url = $this->prepareUrl(trim($detailContent->find('.left_side .image a img')->getAttribute('src')));
            $event->title = trim($detailContent->find('.right_side .main .title h1')->text);
            $event->email = trim($detailContent->find('.left_side .contact .left_s .email')->text);
            $event->url_web = trim($detailContent->find('.left_side .contact .left_s .web a')->getAttribute('href'));
            $event->phone = trim($detailContent->find('.left_side .contact .right_s .phone')->text);
            $event->map_iframe_url = trim($detailContent->find('.left_side .map iframe')->getAttribute('src'));
            $event->place_url_detail = $this->prepareUrl(trim($detailContent->find('.right_side .main .title .info .item')[0]->find('span a')->getAttribute('href')));
            $event->place = trim($detailContent->find('.right_side .main .title .info .item')[0]->find('span a')->text);
            $event->entry_amount = trim($detailContent->find('.right_side .main .title .info .item')[2]->find('span')->text);
            $event->description = trim($detailContent->find('.right_side .main .desc')->innerHtml);
            $event->drink_list_url = trim($detailContent->find('.right_side .main .foot a')->getAttribute('href'));
            $event->social_url = trim($detailContent->find('.right_side .main .foot .social a')->getAttribute('href'));
            
            $datetime = explode("<br />", trim($detailContent->find('.right_side .main .title .info .item')[1]->find('span')->innerHtml)); // date + time
            $address = explode("<br />", trim($detailContent->find('.left_side .contact .left_s .address')->innerHtml)); // street + city
            
            $event->date = trim($datetime[0]);
            $event->time = trim($datetime[1]);
            $event->street = trim($address[0]);
            $event->city = trim($address[1]);
    
            var_dump($event);
            
            // ---------------------------
            
        }catch(\Exception $e){
            $this->logger->log(Logger::ALERT, "Error while loading the list of events: ".$e->getMessage());
            throw new ParserException("Error while loading the list of events: ".$e->getMessage());
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
        $decodedUrl = html_entity_decode(rawurldecode($url));
        return $this->baseUrl.$decodedUrl;
    }
    
}

