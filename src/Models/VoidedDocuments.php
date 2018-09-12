<?php

namespace Cpe\Models;

use Cpe\ModelInterface;
use Cpe\Elements\Element;
use Sabre\Xml\Writer;
use Sabre\Xml\Service;

class VoidedDocuments implements ModelInterface
{
	private $element = 'VoidedDocuments';

	private $currencyID = '';

	private $content = [];

	public function setUBLExtensions()
	{
		$UBLExtensions = new Element(EXT, 'UBLExtensions');

		$UBLExtension = new Element(EXT, 'UBLExtension');

		$ExtensionContent = (new Element(EXT, 'ExtensionContent'))->setValue('');

		$UBLExtension->setValue($ExtensionContent);

		$UBLExtensions->setValue($UBLExtension);

		$this->content[] = $UBLExtensions;

		return $this;
	}

	public function setUBLVersionID($version = '2.0')
	{
		$this->content[] = (new Element(CBC, 'UBLVersionID'))->setValue($version);

		return $this;
	}

	public function setCustomizacionID($version = '1.0')
	{
		$this->content[] = (new Element(CBC, 'CustomizationID'))->setValue($version);

		return $this;
	}

	public function setID($serieNumero = '')
	{
		$this->content[] = (new Element(CBC, 'ID'))->setValue($serieNumero);

		return $this;
	}

	public function setIssueDate($fechaEmision = '')
	{
		$this->content[] = (new Element(CBC, 'IssueDate'))->setValue($fechaEmision);

		return $this;
	}

	public function setReferenceDate($fechaGeneracion = '')
	{
		$this->content[] = (new Element(CBC, 'ReferenceDate'))->setValue($fechaGeneracion);

		return $this;
	}

	public function setSignature($emisor)
	{
		$Signature = new Element(CAC, 'Signature');

		$ID = (new Element(CBC, 'ID'))->setValue('sign'. $emisor->getNumeroDocumentoIdentidad());

		$Signature->setValue($ID);

		$SignatoryParty = new Element(CAC, 'SignatoryParty');

		$PartyIdentification = (new Element(CAC, 'PartyIdentification'))->setValue((new Element(CBC, 'ID'))->setValue($emisor->getNumeroDocumentoIdentidad()));

		$SignatoryParty->setValue($PartyIdentification);

		$PartyName = (new Element(CAC, 'PartyName'))->setValue((new Element(CBC, 'Name'))->setValue($emisor->getRazonSocial()));

		$SignatoryParty->setValue($PartyName);

		$Signature->setValue($SignatoryParty);

		$DigitalSignatureAttachment = new Element(CAC, 'DigitalSignatureAttachment');

		$ExternalReference = (new Element(CAC, 'ExternalReference'))->setValue((new Element(CBC, 'URI'))->setValue('#Sign'. $emisor->getNumeroDocumentoIdentidad()));

		$DigitalSignatureAttachment->setValue($ExternalReference);

		$Signature->setValue($DigitalSignatureAttachment);

		$this->content[] = $Signature;

		return $this;
	}

	public function setAccountingSupplierParty($emisor)
	{
		$AccountingSupplierParty = new Element(CAC, 'AccountingSupplierParty');

		$CustomerAssignedAccountID = (new Element(CBC, 'CustomerAssignedAccountID'))->setValue($emisor->getNumeroDocumentoIdentidad());

		$AdditionalAccountID = (new Element(CBC, 'AdditionalAccountID'))->setValue($emisor->getCodigoDocumentoIdentidad());

		$Party = (new Element(CAC, 'Party'))->setValue((new Element(CAC, 'PartyLegalEntity'))->setValue((new Element(CBC, 'RegistrationName'))->setValue($emisor->getRazonSocial())));

		$AccountingSupplierParty->setValue($CustomerAssignedAccountID)
														->setValue($AdditionalAccountID)
														->setValue($Party);

		$this->content[] = $AccountingSupplierParty;
		
		return $this;
	}

	public function setVoidedDocumentsLine($documentos)
	{
		foreach ($documentos as $documento)
		{
			$VoidedDocumentsLine = new Element(SAC, 'VoidedDocumentsLine');

			$VoidedDocumentsLine->setValue((new Element(CBC, 'LineID'))->setValue($documento->getNumeroItem()));

			$VoidedDocumentsLine->setValue((new Element(CBC, 'DocumentTypeCode'))->setValue($documento->getTipoDocumento()));

			$VoidedDocumentsLine->setValue((new Element(SAC, 'DocumentSerialID'))->setValue($documento->getSerie()));

			$VoidedDocumentsLine->setValue((new Element(SAC, 'DocumentNumberID'))->setValue($documento->getCodigo()));

			$VoidedDocumentsLine->setValue((new Element(SAC, 'VoidReasonDescription'))->setValue($documento->getMotivo()));

			$this->content[] = $VoidedDocumentsLine;
		}

		return $this;
	}

	public function xmlSerialize(Writer $writer) 
  {
  	$writer->write($this->content);
  }

  public function getXml()
  {
  	$xmlService = new Service();

  	$xmlService->namespaceMap = ['urn:sunat:names:specification:ubl:peru:schema:xsd:VoidedDocuments-1' => '',
					                       'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2' => 'cac',
					                       'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2' => 'cbc',
																 //'urn:un:unece:uncefact:documentation:2' => 'ccts',
					                       'http://www.w3.org/2000/09/xmldsig#' => 'ds',
					                       'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2' => 'ext',
																 //'urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2' => 'qdt',
					                       'urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1'=>'sac',
																 //'urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2' => 'udt',
																 'http://www.w3.org/2001/XMLSchema-instance' => 'xsi'];

		//$xmlService->namespaceMap = NAMESPACEMAP;

  	$strXML = $xmlService->write($this->element, $this);

  	$strXML = str_replace('<?xml version="1.0"?>', '<?xml version="1.0" encoding="ISO-8859-1" standalone="no" ?>', $strXML);

  	return $strXML;
  }
}