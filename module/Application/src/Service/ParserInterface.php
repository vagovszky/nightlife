<?php

namespace Application\Service;

interface ParserInterface {
    
    public function parse();
    
    public function setBaseUrl($baseUrl);
    
    public function setListUrl($listUrl);
    
    public function emptyTable();
    
    public function setDebugMode($debugMode = true);
    
}

