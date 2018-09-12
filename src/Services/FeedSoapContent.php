<?php

namespace Cpe\Services;


use Sabre\Xml\Writer;
use Sabre\Xml\XmlSerializable;
use Cpe\Elements\Element;

class FeedSoapContent implements XmlSerializable
{
	const SOAPENV = 'soapenv';

  private $method = 'sendBill';
  private $userName;
  private $password;
  private $fileName;
  private $contentFile;
  private $ticket;

  public function setMethod($method)
  {
    $this->method = $method;
    return $this;
  }

  public function setUserName($userName)
  {
    $this->userName = $userName;
    return $this;
  }

  public function setPassword($password)
  {
    $this->password = $password;
    return $this;
  }

  public function setFileName($fileName)
  {
    $this->fileName = $fileName;
    return $this;
  }

  public function setContentFile($contentFile)
  {
    $this->contentFile = $contentFile;
    return $this;
  }

  public function setTicket($ticket)
  {
    $this->ticket = $ticket;
    return $this;
  }

  function xmlSerialize(Writer $writer) 
  {
    if($this->method == 'getStatus')
    {
      $contentMethod = ['ticket' => $this->ticket];
    }
    else
    {
      $contentMethod = ['fileName'    => $this->fileName,
                        'contentFile' => $this->contentFile
                       ];
    }

    $Header = new Element('soapenv:', 'Header');

    $Security = new Element('wsse:', 'Security');

    $UsernameToken = new Element('wsse:', 'UsernameToken');

    $Username = (new Element('wsse:', 'Username'))->setValue($this->userName);
    $Password = (new Element('wsse:', 'Password'))->setValue($this->password);

    $UsernameToken->setValue($Username)
    							->setValue($Password);

    $Security->setValue($UsernameToken);

    $Header->setValue($Security);

    $Body = new Element('soapenv:', 'Body');

    $Ser = (new Element('ser:', $this->method))->setValue($contentMethod);

    $Body->setValue($Ser);

    $writer->write([$Header, $Body]);

    /*$writer->write([
      Schema::SOAPENV.'Header'=> [
        Schema::WSSE.'Security' => [
          Schema::WSSE.'UsernameToken' => [
            Schema::WSSE.'Username'=> $this->userName,
            Schema::WSSE.'Password'=> $this->password
          ]
        ]
      ],
      Schema::SOAPENV.'Body'=> [
        Schema::SER.$this->method => $contentMethod
      ]
    ]);*/
  }
}