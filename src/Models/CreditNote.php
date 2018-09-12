<?php

namespace Cpe\Models;

use Cpe\ModelInterface;
use Cpe\Elements\Element;
use Sabre\Xml\Writer;
use Sabre\Xml\Service;

class CreditNote implements ModelInterface
{
	private $element = 'CreditNote';

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

	public function setUBLVersionID($version = '2.1')
	{
		$this->content[] = (new Element(CBC, 'UBLVersionID'))->setValue($version);

		return $this;
	}

	public function setCustomizacionID($version = '2.0')
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

	public function setIssueTime($horaEmision = '')
	{
		$this->content[] = (new Element(CBC, 'IssueTime'))->setValue($horaEmision);

		return $this;
	}

	public function setNote($leyenda)
	{
		foreach ($leyenda as $itemLeyenda)
		{
			$this->content[] = (new Element(CBC, 'Note'))->setAttribute('languageLocaleID', $itemLeyenda->getCodigo())
																									 ->setValue($itemLeyenda->getDescripcion());
		}

		return $this;
	}

	public function setDocumentCurrencyCode($codigoTipoMoneda = '')
	{
		$this->currencyID = $codigoTipoMoneda;

		$this->content[] = (new Element(CBC, 'DocumentCurrencyCode'))->setAttribute('listID', 'ISO 4217 Alpha')
																																 ->setAttribute('listName', 'Currency')
																																 ->setAttribute('listAgencyName', 'United Nations Economic Commission for Europe')
																																 ->setValue($codigoTipoMoneda);

		return $this;
	}

	public function setDiscrepancyResponse($notaCredito)
	{
		$DiscrepancyResponse = new Element(CAC, 'DiscrepancyResponse');

		$DiscrepancyResponse->setValue((new Element(CBC, 'ResponseCode'))->setAttribute('listAgencyName', 'PE:SUNAT')
																																		 ->setAttribute('listName', 'Tipo de nota de credito')
																																		 ->setAttribute('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo09')
																																		 ->setValue($notaCredito['tipo']));

		$DiscrepancyResponse->setValue((new Element(CBC, 'Description'))->setValue($notaCredito['motivo_sustento']));

		$this->content[] = $DiscrepancyResponse;

		return $this;
	}

	public function setBillingReference($documentoModifica)
	{
		$BillingReference = new Element(CAC, 'BillingReference');

		$InvoiceDocumentReference = new Element(CAC, 'InvoiceDocumentReference');

		$InvoiceDocumentReference->setValue((new Element(CBC, 'ID'))->setValue($documentoModifica['nro_documento']))
														 ->setValue((new Element(CBC, 'DocumentTypeCode'))->setAttribute('listAgencyName', 'PE:SUNAT')
																																							->setAttribute('listName', 'Tipo de Documento')
																																							->setAttribute('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01')
																																							->setValue($documentoModifica['codigo']));

		$BillingReference->setValue($InvoiceDocumentReference);

		$this->content[] = $BillingReference;

		return $this;
	}

	public function setDespatchDocumentReference($guias)
	{
		if(is_array($guias))
		{
			foreach ($guias as $item)
			{
				$this->content[] = (new Element(CAC, 'DespatchDocumentReference'))->setValue((new Element(CBC, 'ID'))->setValue($item['nro_guia']))
																																					->setValue((new Element(CBC, 'DocumentTypeCode'))->setAttribute('listAgencyName', 'PE:SUNAT')
																																																													 ->setAttribute('listName', 'Tipo de Documento')
																																																													 ->setAttribute('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01')
																																																													 ->setValue($item['codigo']));
			}			
		}
		
		return $this;
	}

	public function setAdditionalDocumentReference($documento)
	{
		if(is_array($documento))
		{
			$AdditionalDocumentReference = new Element(CAC, 'AdditionalDocumentReference');

			$AdditionalDocumentReference->setValue((new Element(CBC, 'ID'))->setValue($documento['nro_documento']))
															  	->setValue((new Element(CBC, 'DocumentTypeCode'))->setAttribute('listAgencyName', 'PE:SUNAT')
																																								 	 ->setAttribute('listName', 'Documentos Relacionados')
																																								 	 ->setAttribute('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo12')
																																								 	 ->setValue($documento['codigo']));

			$this->content[] = $AdditionalDocumentReference;
		}

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
		$PartyIdentification = (new Element(CAC, 'PartyIdentification'))->setValue((new Element(CBC, 'ID'))->setAttribute('schemeID', $emisor->getCodigoDocumentoIdentidad())
																																																			 ->setAttribute('schemeName', 'Documento de Identidad')
																																																			 ->setAttribute('schemeAgencyName', 'PE:SUNAT')
																																																			 ->setAttribute('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06')
																																																			 ->setValue($emisor->getNumeroDocumentoIdentidad()));

		$PartyName = (new Element(CAC, 'PartyName'))->setValue((new Element(CBC, 'Name'))->setValue($emisor->getNombreComercial()));

		$PartyLegalEntity = (new Element(CAC, 'PartyLegalEntity'))->setValue((new Element(CBC, 'RegistrationName'))->setValue($emisor->getRazonSocial()))
																															->setValue((new Element(CAC, 'RegistrationAddress'))->setValue((new Element(CBC, 'AddressTypeCode'))->setAttribute('listAgencyName', 'PE:SUNAT')
																																																																																	->setAttribute('listName', 'Establecimientos anexos')
																																																																																	->setValue($emisor->getCodigoDomicilioFiscal())));

		$this->content[] = (new Element(CAC, 'AccountingSupplierParty'))->setValue((new Element(CAC, 'Party'))->setValue($PartyIdentification)->setValue($PartyName)->setValue($PartyLegalEntity));
		
		return $this;
	}

	public function setAccountingCustomerParty($cliente)
	{
		$PartyIdentification = (new Element(CAC, 'PartyIdentification'))->setValue((new Element(CBC, 'ID'))->setAttribute('schemeID', $cliente->getCodigoDocumentoIdentidad())
																																																		 ->setAttribute('schemeName', 'Documento de Identidad')
																																																		 ->setAttribute('schemeAgencyName', 'PE:SUNAT')
																																																		 ->setAttribute('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06')
																																																		 ->setValue($cliente->getNumeroDocumentoIdentidad()));

		$PartyLegalEntity = (new Element(CAC, 'PartyLegalEntity'))->setValue((new Element(CBC, 'RegistrationName'))->setValue($cliente->getRazonSocial()));

		$Party = (new Element(CAC, 'Party'))->setValue($PartyIdentification)->setValue($PartyLegalEntity);

		$this->content[] = (new Element(CAC, 'AccountingCustomerParty'))->setValue($Party);

		return $this;
	}

	public function setTaxTotal($montoTotal = '', $taxSubTotal, $retorno = true)
	{
		$TaxTotal = new Element(CAC, 'TaxTotal');

		if($montoTotal != '')
		{
			$TaxTotal->setValue((new Element(CBC, 'TaxAmount'))->setAttribute('currencyID', $this->currencyID)->setValue($montoTotal));
		}

		foreach ($taxSubTotal as $itemTaxSubTotal)
		{
			$TaxTotal->setValue($this->TaxSubtotal($itemTaxSubTotal));			
		}

		if($retorno)
		{
			return $TaxTotal;			
		}
		else
		{
			$this->content[] = $TaxTotal;

			return $this;
		}
	}

	public function TaxSubtotal($taxSubTotal)
	{
		$TaxScheme = new Element(CAC, 'TaxScheme');
		$TaxScheme->setValue((new Element(CBC, 'ID'))->setAttribute('schemeName', 'Codigo de tributos')
																								 ->setAttribute('schemeAgencyName', 'PE:SUNAT')
																								 ->setAttribute('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05')
																								 ->setValue($taxSubTotal->getCodigoTributo()));
		$TaxScheme->setValue((new Element(CBC, 'Name'))->setValue($taxSubTotal->getNombreTributo()));
		$TaxScheme->setValue((new Element(CBC, 'TaxTypeCode'))->setValue($taxSubTotal->getCodigoInternacionalTributo()));

		$TaxCategory = new Element(CAC, 'TaxCategory');
		
		if($taxSubTotal->getTasaTributo() != '')
		{	
			$TaxCategory->setValue((new Element(CBC, 'Percent'))->setValue($taxSubTotal->getTasaTributo()));
		}
		
		if($taxSubTotal->getCodigoAfectacionTributo() != '')
		{
			$TaxCategory->setValue((new Element(CBC, 'TaxExemptionReasonCode'))->setAttribute('listAgencyName', 'PE:SUNAT')
																																				 ->setAttribute('listName', 'Afectacion del IGV')
																																				 ->setAttribute('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07')
																																				 ->setValue($taxSubTotal->getCodigoAfectacionTributo()));
		}
	
		if($taxSubTotal->getTipoSistemaTributo() != '')
		{
			$TaxCategory->setValue((new Element(CBC , 'TierRange'))->setValue($taxSubTotal->getTipoSistemaTributo()));
		}

		$TaxCategory->setValue($TaxScheme);

		$TaxSubtotal = new Element(CAC, 'TaxSubtotal');
		$TaxSubtotal->setValue((new Element(CBC, 'TaxableAmount'))->setAttribute('currencyID', $this->currencyID)->setValue($taxSubTotal->getTotalValor()))
								->setValue((new Element(CBC, 'TaxAmount'))->setAttribute('currencyID', $this->currencyID)->setValue($taxSubTotal->getImporteTributo()));
		$TaxSubtotal->setValue($TaxCategory);

		return $TaxSubtotal;
	}

	public function setLegalMonetaryTotal($montosTotales)
	{
		$LegalMonetaryTotal = new Element(CAC, 'LegalMonetaryTotal');
		
		if($montosTotales->getTotalDescuentos() != '')
		{
			$AllowanceTotalAmount = (new Element(CBC, 'AllowanceTotalAmount'))->setAttribute('currencyID', $this->currencyID)->setValue($montosTotales->getTotalDescuentos());

			$LegalMonetaryTotal->setValue($AllowanceTotalAmount);
		}

		if($montosTotales->getTotalOtrosCargos() != '')
		{
			$ChargeTotalAmount = (new Element(CBC, 'ChargeTotalAmount'))->setAttribute('currencyID', $this->currencyID)->setValue($montosTotales->getTotalOtrosCargos());

			$LegalMonetaryTotal->setValue($ChargeTotalAmount);
		}

		if($montosTotales->getImporteTotal() != '')
		{			
			$PayableAmount = (new Element(CBC, 'PayableAmount'))->setAttribute('currencyID', $this->currencyID)->setValue($montosTotales->getImporteTotal());

			$LegalMonetaryTotal->setValue($PayableAmount);
		}

		$this->content[] = $LegalMonetaryTotal;

		return $this;
	}

	public function setCreditNoteLine($items)
	{
		foreach ($items as $item)
		{
			$CreditNoteLine = new Element(CAC, 'CreditNoteLine');

			$ID = (new Element(CBC, 'ID'))->setValue($item->getNumeroItem());

			$CreditNoteLine->setValue($ID);

			$CreditedQuantity = (new Element(CBC, 'CreditedQuantity'))->setAttribute('unitCode', $item->getCodigoUnidadMedida())
																																->setAttribute('unitCodeListID', 'UN/ECE rec 20')
																																->setAttribute('unitCodeListAgencyName', 'United Nations Economic Commission for Europe')
																																->setValue($item->getCantidad());

			$CreditNoteLine->setValue($CreditedQuantity);

			$LineExtensionAmount = (new Element(CBC, 'LineExtensionAmount'))->setAttribute('currencyID', $this->currencyID)->setValue($item->getValorVenta());

			$CreditNoteLine->setValue($LineExtensionAmount);

			$PricingReference = new Element(CAC, 'PricingReference');

			$AlternativeConditionPrice = new Element(CAC, 'AlternativeConditionPrice');

			$AlternativeConditionPrice->setValue((new Element(CBC, 'PriceAmount'))->setAttribute('currencyID', $this->currencyID)->setValue($item->getPrecioVentaUnitario()))
																->setValue((new Element(CBC, 'PriceTypeCode'))->setAttribute('listName', 'Tipo de Precio')
																																							->setAttribute('listAgencyName', 'PE:SUNAT')
																																							->setAttribute('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16')
																																							->setValue($item->getCodigoTipoPrecio()));

			$PricingReference->setValue($AlternativeConditionPrice);

			$CreditNoteLine->setValue($PricingReference);

			$TaxTotal = $this->setTaxTotal($item->getMontoTotal(), $item->getTaxSubTotal());

			$CreditNoteLine->setValue($TaxTotal);

			$Item = new Element(CAC, 'Item');

			$Description = (new Element(CBC, 'Description'))->setValue($item->getDescripcion());

			$Item->setValue($Description);

			$SellersItemIdentification = (new Element(CAC, 'SellersItemIdentification'))->setValue((new Element(CBC, 'ID'))->setValue($item->getCodigoProducto()));

			$Item->setValue($SellersItemIdentification);

			if($item->getCodigoProductoGS() != '')
			{				
				$StandardItemIdentification = (new Element(CAC, 'StandardItemIdentification'))->setValue((new Element(CBC, 'ID'))->setValue($item->getCodigoProductoGS()));

				$Item->setValue($StandardItemIdentification);
			}

			if($item->getCodigoProductoSunat() != '')
			{				
				$CommodityClassification = (new Element(CAC, 'CommodityClassification'))->setValue((new Element(CBC, 'ItemClassificationCode'))->setAttribute('listID', 'UNSPSC')
																																																																			 ->setAttribute('listAgencyName', 'GS1 US')
																																																																			 ->setAttribute('listName', 'Item Classification')
																																																																			 ->setValue($item->getCodigoProductoSunat()));

				$Item->setValue($CommodityClassification);
			}


			if($item->getGastosRenta() != '' || $item->getCodigoGastosRenta() != '' || $item->getNumeroPlacaVehiculo() != '')
			{				
				$AdditionalItemProperty = (new Element(CAC, 'AdditionalItemProperty'))->setValue((new Element(CBC, 'Name'))->setValue($item->getGastosRenta()))
																																							->setValue((new Element(CBC, 'NameCode'))->setAttribute('listName', 'Propiedad del item')
																																																											 ->setAttribute('listAgencyName', 'PE:SUNAT')
																																																											 ->setAttribute('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo55')
																																																											 ->setValue($item->getCodigoGastosRenta()))
																																							->setValue((new Element(CBC, 'Value'))->setValue($item->getNumeroPlacaVehiculo()));

				$Item->setValue($AdditionalItemProperty);
			}

			$CreditNoteLine->setValue($Item);

			$Price = (new Element(CAC, 'Price'))->setValue((new Element(CBC, 'PriceAmount'))->setAttribute('currencyID', $this->currencyID)->setValue($item->getValorUnitario()));

			$CreditNoteLine->setValue($Price);

			$this->content[] = $CreditNoteLine;
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

  	$xmlService->namespaceMap = ['urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2' => '',
					                       'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2' => 'cac',
					                       'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2' => 'cbc',
																 'urn:un:unece:uncefact:documentation:2' => 'ccts',
					                       'http://www.w3.org/2000/09/xmldsig#' => 'ds',
					                       'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2' => 'ext',
																 'urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2' => 'qdt',
					                       //'urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1'=>'sac',
																 'urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2' => 'udt',
																 'http://www.w3.org/2001/XMLSchema-instance' => 'xsi'];

		//$xmlService->namespaceMap = NAMESPACEMAP;

  	$strXML = $xmlService->write($this->element, $this);

  	$strXML = str_replace('<?xml version="1.0"?>', '<?xml version="1.0" encoding="ISO-8859-1" standalone="no" ?>', $strXML);

  	return $strXML;
  }
}