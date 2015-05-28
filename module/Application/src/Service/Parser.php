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
    
    private $base_url;
    private $list_url;
    private $logger;
    
    const PARSER_ENTITY = 'Application\Entity\Event';
    
    public function __construct(EntityManagerInterface $em, Dom $dom){
        $this->em = $em;
        $this->dom = $dom;
    }
    
    public function setBasUrl($base_url){
        $this->base_url = $base_url;
        return $this;
    }
    
    public function setListUrl($list_url){
        $this->list_url = $list_url;
        return $this;
    }
    
    public function setLogger(LoggerInterface $logger){
        $this->logger = $logger;
        return $this;
    }
    
    public function parse(){
        $this->truncateTable();
        foreach($this->getUrlList() as $url){
            $fullUrlDecoded = $this->base_url.rawurldecode($url);
            $this->parseDetail($fullUrlDecoded);
        }
    }
    
    private function parseDetail($url){
        
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
        $this->logger->log(Logger::INFO, "Loading list of events from ".$this->list_url);
        try{
            $this->dom->load($this->list_url);
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
    
}

