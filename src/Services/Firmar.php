<?php

namespace Cpe\Services;

use Cpe\Signature\XMLSecurityDSig;
use Cpe\Signature\XMLSecurityKey;

class Firmar
{
	private $signature = 'SignatureSP';
  private $urlCertKey;
  private $dirName;
  private $fileName;
  private $strXml;
  private $passPFX;
  private $esPFX = false;
  private $nivel = 0;
  private $error;
  
  private $extXML = '.xml';
  private $extZIP = '.zip';
  
  function __construct($datos = [])
  {
  	if($datos['signature'] != '')
  	{
    	$this->signature = $datos['signature'];  		
  	}

    $this->urlCertKey = $datos['url_cert_key'];
    $this->dirName = $datos['dir_name'];
    $this->fileName = $datos['file_name'];
    $this->strXml = $datos['str_xml'];
    $this->passPFX = $datos['pass_pfx'];
    
    if(isset($datos['es_pfx']) && is_bool($datos['es_pfx']))
    {
      $this->esPFX = $datos['es_pfx'];
    }
    
    /*if(isset($datos['nivel']) && ($datos['nivel'] == 1 || $datos['nivel'] == 0))
    {
      $this->nivel = $datos['nivel'];
    }*/
  }
    
  private function prepare()
  {
    if($this->urlCertKey == '')
    {
      $error['url_cert_key'] = 'No esta definido la ruta';
    }
    else
    {
    	if(!file_exists($this->urlCertKey))
    	{
    		$error['url_cert_key'] = 'No esta definido el archivo';
    	}
    }
        
    if($this->dirName == '')
    {
      $error['dir_name'] = 'No esta definido la ruta';
    }
    else
    {
    	if(!file_exists($this->dirName) && !is_writable($this->dirName))
    	{
    		$error['dir_name'] = 'Direcctorio sin permiso de escritura';
    	}
    }

    if($this->fileName == '')
    {
      $error['file_name'] = 'No esta definido el nombre para los archivos';
    }
    
    if($this->strXml == '')
    {
      $error['str_xml'] = 'No esta definido el contenido XML';
    }
    
    if($this->passPFX == '' && $this->esPFX === true)
    {
      $error['pass_pfx'] = 'No esta definido el password para el PFX';
    }
    
    if(count($error) > 0)
    {
      $this->error = $error;
    }
  }
  
  public function generar()
  {
    $this->prepare();
    
    if(count($this->error) > 0)
    {
      $status = 1;
    }
    else
    {
      $docXML = new \DOMDocument();
      
      $docXML->loadXML($this->strXml);
      
      $objDSig = new XMLSecurityDSig('Sign'. $this->signature);
      $objDSig->setCanonicalMethod(XMLSecurityDSig::C14N);
      $objDSig->addReference($docXML, XMLSecurityDSig::SHA1,['http://www.w3.org/2000/09/xmldsig#enveloped-signature'],['force_uri' => true]);

      $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, ['type' => 'private']);
      
      if($this->esPFX)
      {
        $certPFX = file_get_contents($this->urlCertKey);
        openssl_pkcs12_read($certPFX, $certKey, $this->passPFX);        
        $objKey->loadKey($certKey["pkey"]);
        $objDSig->add509Cert($certKey["cert"], true, false, array('subjectName' => true));        
      }
      else
      {    
        $objKey->loadKey(file_get_contents($this->urlCertKey));

        $objDSig->add509Cert(file_get_contents($this->urlCertKey));
      }

      $objDSig->sign($objKey, $docXML->getElementsByTagName('ExtensionContent')->item($this->nivel));
      
      $docXML->save($this->dirName . DIRECTORY_SEPARATOR. $this->fileName . $this->extXML);
      
      $zip = new \ZipArchive();
      $zip->open($this->dirName.DIRECTORY_SEPARATOR.$this->fileName.$this->extZIP, \ZipArchive::CREATE);      

      $zip->addFile($this->dirName.DIRECTORY_SEPARATOR.$this->fileName.$this->extXML, $this->fileName.$this->extXML);      
      $zip->close();
      
      $status = 2;
      
      $data = [
      	'dir_xml' => $this->dirName.DIRECTORY_SEPARATOR.$this->fileName.$this->extXML, 
      	'dir_zip' => $this->dirName.DIRECTORY_SEPARATOR.$this->fileName.$this->extZIP, 
      	'signatureValue' => $objDSig->signatureValue,
      	'digestValue' => $objDSig->digestValue
      	];
    }
    
    return ['status' => $status, 'data' => $data, 'error' => $this->error];
  }
}