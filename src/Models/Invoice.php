<?php

namespace Cpe\Models;

use Cpe\ModelInterface;
use Cpe\Elements\Element;
use Sabre\Xml\Writer;
use Sabre\Xml\Service;

class Invoice implements ModelInterface
{
	private $element = 'Invoice';

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

	public function setDueDate($fechaVencimiento = '')
	{
		$this->content[] = (new Element(CBC, 'DueDate'))->setValue($fechaVencimiento);

		return $this;
	}

	public function setInvoiceTypeCode($tipoDocumento = '', $tipoOperacion)
	{
		$this->content[] = (new Element(CBC, 'InvoiceTypeCode'))->setAttribute('listID', $tipoOperacion)
																														->setAttribute('listAgencyName', 'PE:SUNAT')
																														->setAttribute('listName', 'SUNAT:Identificador de Tipo de Documento')
																														->setAttribute('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01')
																														->setValue($tipoDocumento);

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

	public function setLineCountNumeric($nroItems = 1)
	{
		$this->content[] = (new Element(CBC, 'LineCountNumeric'))->setValue($nroItems);

		return $this;
	}

	public function setInvoicePeriod($fechaInicio = '', $fechaFin = '')
	{
		$this->content[] = (new Element(CAC, 'InvoicePeriod'))->setValue((new Element(CBC, 'StartDate'))->setValue($fechaInicio))
																													->setValue((new Element(CBC, 'EndDate'))->setValue($fechaFin));
		
		return $this;
	}

	public function setOrderReference($nroOrdenCompra = '')
	{
		if($nroOrdenCompra != '')
		{
			$this->content[] = (new Element(CAC, 'OrderReference'))->setValue((new Element(CBC, 'ID'))->setValue($nroOrdenCompra));
		}

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

	public function setAdditionalDocumentReference($documento, $tipo = 1)
	{
		if($tipo == 1) // otro documento
		{
			if(is_array($documento))
			{

				$this->content[] = (new Element(CAC, 'AdditionalDocumentReference'))->setValue((new Element(CBC, 'ID'))->setValue($documento['nro_documento']))
																																						->setValue((new Element(CBC, 'DocumentTypeCode'))->setAttribute('listAgencyName', 'PE:SUNAT')
																																																													 	 ->setAttribute('listName', 'Documento Relacionado')
																																																													 	 ->setAttribute('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo12')
																																																													 	 ->setValue($documento['codigo']));
			}
		}
		elseif($tipo == 2) //para anticipos
		{
			if(is_object($documento))
			{
				$AdditionalDocumentReference = new Element(CAC, 'AdditionalDocumentReference');

				$ID = (new Element(CBC, 'ID'))->setValue($documento->getSerieNumeroComprobante());

				$AdditionalDocumentReference->setValue($ID);

				$DocumentTypeCode = (new Element(CBC, 'DocumentTypeCode'))->setAttribute('listName', 'Documento Relacionado')
																																	->setAttribute('listAgencyName', 'PE:SUNAT')
																																	->setAttribute('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo12')
																																	//->setValue($documento->getTipoComprobanteAnticipado());
																																	->setValue($documento->getIdentificadorPago());

				$AdditionalDocumentReference->setValue($DocumentTypeCode);
				
				$DocumentStatusCode = (new Element(CBC, 'DocumentStatusCode'))->setAttribute('listName', 'anticipo')
																																			->setAttribute('listAgencyName', 'PE:SUNAT')
																																			->setValue($documento->getIdentificadorPago());
				$AdditionalDocumentReference->setValue($DocumentStatusCode);

				$IssuerParty = new Element(CAC, 'IssuerParty');

				$PartyIdentification = new Element(CAC, 'PartyIdentification');

				$ID = (new Element(CBC, 'ID'))->setAttribute('schemeID', $documento->getTipoDocumentoEmisor())
																			->setAttribute('schemeName', 'Documento de Identidad')
																			->setAttribute('schemeAgencyName', 'PE:SUNAT')
																			->setAttribute('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06')
																			->setValue($documento->getNumeroDocumentoEmisor());


				$PartyIdentification->setValue($ID);

				$IssuerParty->setValue($PartyIdentification);

				$AdditionalDocumentReference->setValue($IssuerParty);

				$this->content[] = $AdditionalDocumentReference;

			}
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
		
		if($montosTotales->getTotalValorVenta() != '')
		{
			$LineExtensionAmount = (new Element(CBC, 'LineExtensionAmount'))->setAttribute('currencyID', $this->currencyID)->setValue($montosTotales->getTotalValorVenta());

			$LegalMonetaryTotal->setValue($LineExtensionAmount);
		}

		if($montosTotales->getTotalPrecioVenta() != '')
		{			
			$TaxInclusiveAmount = (new Element(CBC, 'TaxInclusiveAmount'))->setAttribute('currencyID', $this->currencyID)->setValue($montosTotales->getTotalPrecioVenta());

			$LegalMonetaryTotal->setValue($TaxInclusiveAmount);
		}

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

		if($montosTotales->getMontoPrepagado() != '')
		{			
			$PrepaidAmount = (new Element(CBC, 'PrepaidAmount'))->setAttribute('currencyID', $this->currencyID)->setValue($montosTotales->getMontoPrepagado());

			$LegalMonetaryTotal->setValue($PrepaidAmount);
		}

		if($montosTotales->getMontoRedondeoImporteTotal() != '')
		{			
			$PayableRoundingAmount = (new Element(CBC, 'PayableRoundingAmount'))->setAttribute('currencyID', $this->currencyID)->setValue($montosTotales->getMontoRedondeoImporteTotal());
			
			$LegalMonetaryTotal->setValue($PayableRoundingAmount);
		}

		if($montosTotales->getImporteTotal() != '')
		{			
			$PayableAmount = (new Element(CBC, 'PayableAmount'))->setAttribute('currencyID', $this->currencyID)->setValue($montosTotales->getImporteTotal());

			$LegalMonetaryTotal->setValue($PayableAmount);
		}

		$this->content[] = $LegalMonetaryTotal;

		return $this;
	}

	public function setInvoiceLine($items)
	{
		foreach ($items as $item)
		{
			$InvoiceLine = new Element(CAC, 'InvoiceLine');

			$ID = (new Element(CBC, 'ID'))->setValue($item->getNumeroItem());

			$InvoiceLine->setValue($ID);

			$InvoicedQuantity = (new Element(CBC, 'InvoicedQuantity'))->setAttribute('unitCode', $item->getCodigoUnidadMedida())
																																->setAttribute('unitCodeListID', 'UN/ECE rec 20')
																																->setAttribute('unitCodeListAgencyName', 'United Nations Economic Commission for Europe')
																																->setValue($item->getCantidad());

			$InvoiceLine->setValue($InvoicedQuantity);

			$LineExtensionAmount = (new Element(CBC, 'LineExtensionAmount'))->setAttribute('currencyID', $this->currencyID)->setValue($item->getValorVenta());

			$InvoiceLine->setValue($LineExtensionAmount);

			$PricingReference = new Element(CAC, 'PricingReference');

			$AlternativeConditionPrice = new Element(CAC, 'AlternativeConditionPrice');

			$AlternativeConditionPrice->setValue((new Element(CBC, 'PriceAmount'))->setAttribute('currencyID', $this->currencyID)->setValue($item->getPrecioVentaUnitario()))
																->setValue((new Element(CBC, 'PriceTypeCode'))->setAttribute('listName', 'Tipo de Precio')
																																							->setAttribute('listAgencyName', 'PE:SUNAT')
																																							->setAttribute('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16')
																																							->setValue($item->getCodigoTipoPrecio()));

			$PricingReference->setValue($AlternativeConditionPrice);

			$InvoiceLine->setValue($PricingReference);

			//Delivery			

			$detraccion = $item->getDetraccion();

			if(is_object($detraccion))
			{
				$InvoiceLine->setValue($this->setDelivery($detraccion));
			}

			//Descuentos
			if(is_object($item->getDescuentos()))
			{				
				$InvoiceLine->setValue($this->setAllowancecharge($item->getDescuentos()));
			}

			$TaxTotal = $this->setTaxTotal($item->getMontoTotal(), $item->getTaxSubTotal());

			$InvoiceLine->setValue($TaxTotal);

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

			$detraccion = $item->getDetraccion();

			if(is_object($detraccion))
			{
				//matricula embarcación
				if($detraccion->getMatriculaEmbarcacionPesquera('valor') != '')
				{					
					$AdditionalItemProperty = new Element(CAC, 'AdditionalItemProperty');
					$AdditionalItemProperty->setValue((new Element(CBC, 'Name'))->setValue($detraccion->getMatriculaEmbarcacionPesquera('concepto')));
					$AdditionalItemProperty->setValue((new Element(CBC, 'NameCode'))->setAttribute('listName', 'Propiedad del item')
																																					->setAttribute('listAgencyName', 'PE:SUNAT')
																																					->setAttribute('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo55')
																																					->setValue($detraccion->getMatriculaEmbarcacionPesquera('codigo')));
					$AdditionalItemProperty->setValue((new Element(CBC, 'Value'))->setValue($detraccion->getMatriculaEmbarcacionPesquera('valor')));
					$Item->setValue($AdditionalItemProperty);
				}
				
				//nombre embarcación
				if($detraccion->getNombreEmbarcacionPesquera('valor') != '')
				{				
					$AdditionalItemProperty = new Element(CAC, 'AdditionalItemProperty');
					$AdditionalItemProperty->setValue((new Element(CBC, 'Name'))->setValue($detraccion->getNombreEmbarcacionPesquera('concepto')));
					$AdditionalItemProperty->setValue((new Element(CBC, 'NameCode'))->setAttribute('listName', 'Propiedad del item')
																																					->setAttribute('listAgencyName', 'PE:SUNAT')
																																					->setAttribute('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo55')
																																					->setValue($detraccion->getNombreEmbarcacionPesquera('codigo')));
					$AdditionalItemProperty->setValue((new Element(CBC, 'Value'))->setValue($detraccion->getNombreEmbarcacionPesquera('valor')));
					$Item->setValue($AdditionalItemProperty);
				}
				
				//tipo especie vendida
				if($detraccion->getTipoEspecieVendida('valor') != '')
				{
					$AdditionalItemProperty = new Element(CAC, 'AdditionalItemProperty');
					$AdditionalItemProperty->setValue((new Element(CBC, 'Name'))->setValue($detraccion->getTipoEspecieVendida('concepto')));
					$AdditionalItemProperty->setValue((new Element(CBC, 'NameCode'))->setAttribute('listName', 'Propiedad del item')
																																					->setAttribute('listAgencyName', 'PE:SUNAT')
																																					->setAttribute('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo55')
																																					->setValue($detraccion->getTipoEspecieVendida('codigo')));
					$AdditionalItemProperty->setValue((new Element(CBC, 'Value'))->setValue($detraccion->getTipoEspecieVendida('valor')));
					$Item->setValue($AdditionalItemProperty);
				}
				
				//lugar descarga especie vendida
				if($detraccion->getLugarDescarga('valor') != '')
				{					
					$AdditionalItemProperty = new Element(CAC, 'AdditionalItemProperty');
					$AdditionalItemProperty->setValue((new Element(CBC, 'Name'))->setValue($detraccion->getLugarDescarga('concepto')));
					$AdditionalItemProperty->setValue((new Element(CBC, 'NameCode'))->setAttribute('listName', 'Propiedad del item')
																																					->setAttribute('listAgencyName', 'PE:SUNAT')
																																					->setAttribute('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo55')
																																					->setValue($detraccion->getLugarDescarga('codigo')));
					$AdditionalItemProperty->setValue((new Element(CBC, 'Value'))->setValue($detraccion->getLugarDescarga('valor')));
					$Item->setValue($AdditionalItemProperty);
				}
				
				//lugar descarga especie vendida
				if($detraccion->getFechaDescarga('valor') != '')
				{
					$AdditionalItemProperty = new Element(CAC, 'AdditionalItemProperty');
					$AdditionalItemProperty->setValue((new Element(CBC, 'Name'))->setValue($detraccion->getFechaDescarga('concepto')));
					$AdditionalItemProperty->setValue((new Element(CBC, 'NameCode'))->setAttribute('listName', 'Propiedad del item')
																																					->setAttribute('listAgencyName', 'PE:SUNAT')
																																					->setAttribute('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo55')
																																					->setValue($detraccion->getFechaDescarga('codigo')));
					$AdditionalItemProperty->setValue((new Element(CAC, 'UsabilityPeriod'))->setValue((new Element(CBC, 'StartDate'))->setValue($detraccion->getFechaDescarga('valor'))));
					$Item->setValue($AdditionalItemProperty);
				}
				
				//cantidad de especie vendida
				if($detraccion->getCantidadEspecieVendida('valor') != '')
				{
					$AdditionalItemProperty = new Element(CAC, 'AdditionalItemProperty');
					$AdditionalItemProperty->setValue((new Element(CBC, 'Name'))->setValue($detraccion->getCantidadEspecieVendida('concepto')));
					$AdditionalItemProperty->setValue((new Element(CBC, 'NameCode'))->setAttribute('listName', 'Propiedad del item')
																																					->setAttribute('listAgencyName', 'PE:SUNAT')
																																					->setAttribute('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo55')
																																					->setValue($detraccion->getCantidadEspecieVendida('codigo')));
					$AdditionalItemProperty->setValue((new Element(CBC, 'ValueQuantity'))->setAttribute('unitCode', 'TNE')
																																							 ->setAttribute('unitCodeListID', 'UN/ECE rec 20')
																																							 ->setAttribute('unitCodeListAgencyName', 'United Nations Economic Commission for Europe')
																																							 ->setValue($detraccion->getCantidadEspecieVendida('valor')));
					$Item->setValue($AdditionalItemProperty);
				}
			}

			$InvoiceLine->setValue($Item);

			$Price = (new Element(CAC, 'Price'))->setValue((new Element(CBC, 'PriceAmount'))->setAttribute('currencyID', $this->currencyID)->setValue($item->getValorUnitario()));

			$InvoiceLine->setValue($Price);

			$this->content[] = $InvoiceLine;
		}

		return $this;
	}

	public function setAllowancecharge($descuentos, $retorno = true)
	{	
		if(is_object($descuentos))
		{
			$ChargeIndicator = (new Element(CBC, 'ChargeIndicator'))->setValue($descuentos->getIndicador());

			$AllowanceChargeReasonCode = (new Element(CBC, 'AllowanceChargeReasonCode'))->setAttribute('listAgencyName', 'PE:SUNAT')
																																									->setAttribute('listName', 'Cargo/descuento')
																																									->setAttribute('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo53')
																																									->setValue($descuentos->getCodigo());

			$MultiplierFactorNumeric = (new Element(CBC, 'MultiplierFactorNumeric'))->setValue($descuentos->getFactorPorcentaje());

			$Amount = (new Element(CBC, 'Amount'))->setAttribute('currencyID', $this->currencyID)->setValue($descuentos->getImporte());

			$BaseAmount = (new Element(CBC, 'BaseAmount'))->setAttribute('currencyID', $this->currencyID)->setValue($descuentos->getImporteBase());

			$AllowanceCharge = new Element(CAC, 'AllowanceCharge');

			$AllowanceCharge->setValue($ChargeIndicator);
			$AllowanceCharge->setValue($AllowanceChargeReasonCode);
			$AllowanceCharge->setValue($MultiplierFactorNumeric);
			$AllowanceCharge->setValue($Amount);
			$AllowanceCharge->setValue($BaseAmount);

			if($retorno)
			{
				return $AllowanceCharge;
			}
			else
			{			
				$this->content[] = $AllowanceCharge;
			}
		}

		return $this;
	}

	//Detracciones
	public function setPaymentMeans($detraccion)
	{
		if(is_object($detraccion))
		{
			$PaymentMeans = new Element(CAC, 'PaymentMeans');

			$PaymentMeans->setValue((new Element(CBC, 'ID'))->setValue('L1'));

			$PaymentMeans->setValue((new Element(CBC, 'PaymentMeansCode'))->setAttribute('listID', 'UN/ECE 4461')->setValue('001'));

			$PayeeFinancialAccount = new Element(CAC, 'PayeeFinancialAccount');

			$PayeeFinancialAccount->setValue((new Element(CBC, 'ID'))->setValue($detraccion->getNroCuentaBancaria()));

			$PaymentMeans->setValue($PayeeFinancialAccount);

			$this->content[] = $PaymentMeans;
		}

		return $this;
	}

	public function setPaymentTerms($detraccion)
	{
		if(is_object($detraccion))
		{		
			$PaymentTerms = new Element(CAC, 'PaymentTerms');

			$PaymentMeansID = (new Element(CBC, 'PaymentMeansID'))->setAttribute('schemeName', 'Codigo de detraccion')
																														->setAttribute('schemeAgencyName', 'PE:SUNAT')
																														->setAttribute('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo54')
																														->setValue($detraccion->getCodigo());

			$Amount = (new Element(CBC, 'Amount'))->setAttribute('currencyID', $detraccion->getTipoMoneda())
																						->setValue($detraccion->getMonto());

			$PaymentPercent = (new Element(CBC, 'PaymentPercent'))->setValue($detraccion->getPorcentaje());

			$PaymentTerms->setValue($PaymentMeansID);

			$PaymentTerms->setValue($PaymentPercent);

			$PaymentTerms->setValue($Amount);

			$this->content[] = $PaymentTerms;
		}

		return $this;
	}

	//Información Adicional  - Anticipos
	public function setPrepaidPayment($anticipo)
	{
		if(is_object($anticipo))
		{
			$PrepaidPayment = (new Element(CAC, 'PrepaidPayment'))->setValue((new Element(CBC, 'ID'))->setAttribute('schemeName', 'Anticipo')
																																															 ->setAttribute('schemeAgencyName', 'PE:SUNAT')
																																															 ->setValue($anticipo->getIdentificadorPago()))
																														->setValue((new Element(CBC, 'PaidAmount'))->setAttribute('currencyID', $this->currencyID)
																																																			 ->setValue($anticipo->getMontoAnticipado()))
																														/*->setValue((new Element(CBC, 'PaidDate'))->setValue($fechaPago))*/;

			$this->content[] = $PrepaidPayment;
		}	

		return $this;
	}

	//Detracción transporte de carga	@tipo = 1
  public function setDelivery($objeto, $tipo = 1, $retorno = true)
  {
  	if(is_object($objeto))
  	{
  		if($tipo == 1)
  		{
  			$Delivery = new Element(CAC, 'Delivery');

  			//Punto destino
  			if($objeto->getUbigeoPuntoDestino() != '' && $objeto->getDireccionPuntoDestino() != '')
  			{
  				$Address = (new Element(CAC, 'Address'))->setValue((new Element(CBC, 'ID'))->setAttribute('schemeAgencyName', 'PE:INEI')
  																																									 ->setAttribute('schemeName', 'Ubigeos')
  																																									 ->setValue($objeto->getUbigeoPuntoDestino()))
  																								->setValue((new Element(CAC, 'AddressLine'))->setValue((new Element(CBC, 'Line'))->setValue($objeto->getDireccionPuntoDestino())));

  				$DeliveryLocation = (new Element(CAC, 'DeliveryLocation'))->setValue($Address);

  				$Delivery->setValue($DeliveryLocation);
  			}

  			//Punto Origen
  			if($objeto->getUbigeoPuntoOrigen() != '' && $objeto->getDireccionPuntoOrigen() != '')
  			{
  				$DespatchAddress = (new Element(CAC, 'DespatchAddress'))->setValue((new Element(CBC, 'ID'))->setAttribute('schemeAgencyName', 'PE:INEI')
  																																																	 ->setAttribute('schemeName', 'Ubigeos')
  																																																	 ->setValue($objeto->getUbigeoPuntoOrigen()))
  																																->setValue((new Element(CAC, 'AddressLine'))->setValue((new Element(CBC, 'Line'))->setValue($objeto->getDireccionPuntoOrigen())));
  				
  				$Despatch = (new Element(CAC, 'Despatch'))->setValue((new Element(CBC, 'Instructions'))->setValue($objeto->getDetalleViaje()))
  																									->setValue($DespatchAddress);
  			
					$Delivery->setValue($Despatch);
  			}

  			//Val. Ref. Servicio Transporte
  			if($objeto->getValRefServicioTransporte() != '')
  			{
  				$DeliveryTerms = new Element(CAC, 'DeliveryTerms');

  				$DeliveryTerms->setValue((new Element(CBC, 'ID'))->setValue('01'))
  											->setValue((new Element(CBC, 'Amount'))->setAttribute('currencyID', $this->currencyID)
  																														 ->setValue($objeto->getValRefServicioTransporte()));
  			
  				$Delivery->setValue($DeliveryTerms);
  			}

  			//Val. Ref. carga Efectiva
  			if($objeto->getValRefCargaEfectiva() != '')
  			{
  				$DeliveryTerms = new Element(CAC, 'DeliveryTerms');

  				$DeliveryTerms->setValue((new Element(CBC, 'ID'))->setValue('02'))
  											->setValue((new Element(CBC, 'Amount'))->setAttribute('currencyID', $this->currencyID)
  																														 ->setValue($objeto->getValRefCargaEfectiva()));
  			
  				$Delivery->setValue($DeliveryTerms);
  			}

  			//Val. Ref. carga útil nominal
  			if($objeto->getValRefCargaUtil() != '')
  			{
  				$DeliveryTerms = new Element(CAC, 'DeliveryTerms');

  				$DeliveryTerms->setValue((new Element(CBC, 'ID'))->setValue('03'))
  											->setValue((new Element(CBC, 'Amount'))->setAttribute('currencyID', $this->currencyID)
  																														 ->setValue($objeto->getValRefCargaUtil()));
  			
  				$Delivery->setValue($DeliveryTerms);
  			}

  			/*Detalle vehiculo*/
  			$existeTransportEquipment = false;
  			$existeMeasurementDimension = false;

  			$TransportHandlingUnit = new Element(CAC, 'TransportHandlingUnit');

  			$TransportEquipment = new Element(CAC, 'TransportEquipment');

  			//configuración vehicular
  			if($objeto->getConfiguracionVehicular() != '')
  			{
  				$existeTransportEquipment = true;

  				$TransportEquipment->setValue((new Element(CBC, 'SizeTypeCode'))->setAttribute('listAgencyName', 'PE:MTC')
  																																				->setAttribute('listName', 'Configuracion Vehícular')
  																																				->setValue($objeto->getConfiguracionVehicular()));
  			}

  			//Valor referencial
  			if($objeto->getValorReferencial() != '')
  			{
  				$existeTransportEquipment = true;

  				$DeliveryTerms = (new Element(CAC, 'DeliveryTerms'))->setValue((new Element(CBC, 'Amount'))->setAttribute('currencyID', $this->currencyID)
  																																																	 ->setValue($objeto->getValorReferencial()));

  				$TransportEquipment->setValue((new Element(CAC, 'Delivery'))->setValue($DeliveryTerms));
  			}

  			if($existeTransportEquipment)
  			{
  				$TransportHandlingUnit->setValue($TransportEquipment);
  			}

  			//carga útil
  			if($objeto->getCargaUtilVehiculo() != '')
  			{
  				$existeMeasurementDimension = true;

  				$MeasurementDimension = new Element(CAC, 'MeasurementDimension');

  				$MeasurementDimension->setValue((new Element(CBC, 'AttributeID'))->setValue('01'))
  														 ->setValue((new Element(CBC, 'Measure'))->setAttribute('unitCode', 'TNE')
  																																		 //->setAttribute('unitCodeListID', 'UN/ECE rec 20')
  																																		 //->setAttribute('unitCodeListAgencyName', 'United Nations Economic Commission for Europe')
  																																		 ->setValue($objeto->getCargaUtilVehiculo()));
  			
  				$TransportHandlingUnit->setValue($MeasurementDimension);
  			}

  			//carga efectiva
  			if($objeto->getCargaEfectivaVehiculo() != '')
  			{
  				$existeMeasurementDimension = true;

  				$MeasurementDimension = new Element(CAC, 'MeasurementDimension');

  				$MeasurementDimension->setValue((new Element(CBC, 'AttributeID'))->setValue('02'))
  														 ->setValue((new Element(CBC, 'Measure'))->setAttribute('unitCode', 'TNE')
  																																		 //->setAttribute('unitCodeListID', 'UN/ECE rec 20')
  																																		 //->setAttribute('unitCodeListAgencyName', 'United Nations Economic Commission for Europe')
  																																		 ->setValue($objeto->getCargaEfectivaVehiculo()));
  			
  				$TransportHandlingUnit->setValue($MeasurementDimension);
  			}

  			if($existeTransportEquipment || $existeMeasurementDimension)
  			{
  				$Consignment = (new Element(CAC, 'Consignment'))->setValue((new Element(CBC, 'ID'))->setValue('L1'))
  																												->setValue($TransportHandlingUnit);

  				$Shipment = (new Element(CAC, 'Shipment'))->setValue((new Element(CBC, 'ID'))->setValue('L1'))
  																									->setValue($Consignment);

  				$Delivery->setValue($Shipment);
  			}

  			if($retorno)
  			{
  				return $Delivery;  				
  			}
  			else
  			{
  				$this->content[] = $Delivery;
  			}
  		}
  	}

  	return $this;
  }

  //Información Adicional  - Beneficio de hospedaje


  //Información Adicional  - Paquete Turístico

  //Ventas Sector Público

  //Información adicional  a nivel de ítem -  Gastos intereses hipotecarios primera vivienda

  //Migración de documentos autorizados - Carta Porte Aéreo

  //Migración de documentos autorizados - Pago de regalía petrolera

	public function xmlSerialize(Writer $writer) 
  {
  	$writer->write($this->content);
  }

  public function getXml()
  {
  	$xmlService = new Service();

  	$xmlService->namespaceMap = ['urn:oasis:names:specification:ubl:schema:xsd:Invoice-2' => '',
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