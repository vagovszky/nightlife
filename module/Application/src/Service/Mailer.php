<?php

namespace Application\Service;

use Zend\Mail\Transport\TransportInterface;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mime;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerInterface;
use Zend\Log\Logger;

class Mailer implements MailerInterface, LoggerAwareInterface {
    
    private $transport;
    private $xmlPath;
    private $logger;
    private $recipients = array();
    
    public function __construct(TransportInterface $transport) {
        $this->transport = $transport;
    }
    
    public function setLogger(LoggerInterface $logger){
        $this->logger = $logger;
        return $this;
    }
    
    public function setXmlPath($path){
        $this->xmlPath = $path;
        return $this;
    }
    
    public function setRecipients($recipients = array()){
        $this->recipients = $recipients;
        return $this;
    }
    
    public function send() {
        $this->logger->log(Logger::INFO, "Sending email to: ".implode(",", $this->recipients));
        try{
            $message = new Message();
            $message->setTo($this->recipients);
            $message->setFrom('cf8qde01@gmail.com', "XML generator");
            $message->setSubject('The Night Life XML');
            $message->setBody($this->prepareMimeMessage());
            $this->transport->send($message);
            return true;
        }catch(\Exception $e){
            $this->logger->log(Logger::ALERT, "Sending email error: ".$e->getMessage());
            return false;
        }
    }
    
    private function prepareAttachment(){
        $fileContent = fopen($this->xmlPath, 'r');
        $attachment = new MimePart($fileContent);
        $attachment->type = 'application/xml';
        $attachment->filename = 'events.xml';
        $attachment->disposition = Mime\Mime::DISPOSITION_ATTACHMENT;
        $attachment->encoding = Mime\Mime::ENCODING_BASE64;
        return $attachment;
    }
    
    private function prepareMimeMessage(){
        
        $text = new MimePart("This is automaticaly generated message.");
        $text->type = "text/plain";
        
        $xml = $this->prepareAttachment();
                
        $body = new MimeMessage();
        $body->setParts(array($text, $xml));

        return $body;
    }
    
    
}

