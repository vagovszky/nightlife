<?php

namespace Application\Service;

interface MailerInterface{
    
    public function setXmlPath($path);
    
    public function send();
    
    public function setRecipients($recipients = array());
    
}

