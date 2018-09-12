<?php

namespace Cpe\Services;

class FeedSoap extends \SoapClient
{
  private $strXml;
  
  function __construct($wsdl, $options = [], $strXml)
  {
    $this->strXml = $strXml;
    
    parent::__construct($wsdl, $options);
  }
  
  function __doRequest($request, $location, $action, $version, $one_way = 0)
  {
    $dom = new \DOMDocument('1.0');

    try 
    {
      $dom->loadXML($this->strXml);
    } 
      catch (\DOMException $e) 
    {
      die($e->code);
    }

    $request = $dom->saveXML();
    
    return parent::__doRequest($request, $location, $action, $version, $one_way = 0);
  }
  
  public function getFunction()
  {
    return "REQUEST:\n" . parent::__getFunctions() . "\n";
  }
  
  public function getLastReponse()
  {
    return parent::__getLastResponse();
  }
}