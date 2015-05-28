<?php

namespace Application\Service;

interface ParserInterface {
    
    public function parse();
    
    public function setBaseUrl($baseUrl);
    
    public function setListUrl($listUrl);
    
}

