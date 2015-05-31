<?php

namespace Application\Service;

interface ParserInterface {
    
    public function parse();
    
    public function setBaseUrl($baseUrl);
    
    public function setListUrl($listUrl);
    
    public function truncateTable();
    
    public function setDebugMode($debugMode = true);
    
}

