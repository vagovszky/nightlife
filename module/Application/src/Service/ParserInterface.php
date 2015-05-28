<?php

namespace Application\Service;

interface ParserInterface {
    
    public function parse();
    
    public function setBasUrl($base_url);
    
    public function setListUrl($list_url);
    
}

