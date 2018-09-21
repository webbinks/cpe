<?php

namespace Cpe\Comprobantes;

use Cpe\ComprobanteInterface;
use Cpe\Models\DebitNote;
use Cpe\Components\Leyenda;
use Cpe\Components\Cliente;
use Cpe\Components\Emisor;
use Cpe\Components\SubTotalImpuesto;
use Cpe\Components\MontosTotales;
use Cpe\Components\Item;

class NotaDebito implements ComprobanteInterface
{
	private $debitNote;

	private $signature;

	private $tipoDocumento;

	private $serieCorrelativo;

	private $fechaEmision;

	private $horaEmision;

	private $tipoMoneda;

	private $leyendas;

	private $notaDebito;

	private $documentoModifica;

	private $guias;

	private $otroDocumento;

	private $emisor;

	private $cliente;

	private $totalImpuestos;

	private $subTotalImpuesto;

	private $montosTotales;

	private $item;

	public function __construct()
	{
		$this->debitNote = new debitNote();

		$this->debitNote->setUBLExtensions();
		$this->debitNote->setUBLVersionID();
		$this->debitNote->setCustomizacionID();
		$this->setTipoDocumento('08');

		return $this;
	}


	public function setTipoDocumento($tipoDocumento = '')
	{
		$this->tipoDocumento = $tipoDocumento;

		return $this;
	}

	public function setSerieCorrelativo($serieCorrelativo = '')
	{
		$this->serieCorrelativo = $serieCorrelativo;

		return $this;
	}

	public function setFechaEmision($fechaEmision = '')
	{
		$this->fechaEmision = $fechaEmision;

		return $this;
	}

	public function setHoraEmision($horaEmision = '')
	{
		$this->horaEmision = $horaEmision;

		return $this;
	}

	public function setTipoMoneda($tipoMoneda = '')
	{
		$this->tipoMoneda = $tipoMoneda;

		return $this;
	}

	public function setLeyeneda(Leyenda $leyenda)
	{
		$this->leyendas[] = $leyenda;

		return $this;
	}

	public function setNotaDedito($tipoNotaDebito = '', $motivoSustento = '')
	{
		$this->notaDebito = ['tipo' => $tipoNotaDebito, 'motivo_sustento' => $motivoSustento];

		return $this;
	}

	public function setDocumentoModifica($codigo = '', $nroDocumento = '')
	{
		$this->documentoModifica = ['codigo' => $codigo, 'nro_documento' => $nroDocumento];

		return $this;
	}

	public function setGuias($codigo = '', $nroGuia = '')
	{
		$this->guias[] = ['codigo' => $codigo, 'nro_guia' => $nroGuia];

		return $this;
	}

	public function setOtroDocumento($codigo = '', $nroDocumento = '')
	{
		$this->otroDocumento['codigo'] = $codigo;
		$this->otroDocumento['nro_documento'] = $nroDocumento;

		return $this;
	}

	public function setEmisor(Emisor $emisor)
	{
		$this->emisor = $emisor;
		$this->signature = $emisor;

		return $this;
	}

	public function setCliente(Cliente $cliente)
	{
		$this->cliente = $cliente;

		return $this;
	}

	/*
	Monto total de impuestos
	*/
	public function setTotalImpuestos($totalImpuestos = '')
	{
		$this->totalImpuestos = $totalImpuestos;

		return $this;
	}

	/*
	Monto las operaciones gravadas
	Monto las operaciones Exoneradas
	Monto las operaciones inafectas del impuesto (Ver Ejemplo en la página 47)
	Monto las operaciones gratuitas (Ver Ejemplo en la página 48)
	Sumatoria de IGV
	Sumatoria de ISC (Ver Ejemplo en la página 51)
	Sumatoria de Otros Tributos (Ver Ejemplo en la página 52)
	*/
	public function setSubTotalImpuesto(SubTotalImpuesto $subTotalImpuesto)
	{
		$this->subTotalImpuesto[] = $subTotalImpuesto;

		return $this;
	}

	/*
	Total valor de venta
	Total precio de venta (incluye impuestos)
	Monto total de descuentos del comprobante
	Monto total de otros cargos del comprobante
	Importe total de la venta, cesión en uso o del servicio prestado
	La sumatoria de impuestos (Códigos 1000+1016+2000+9999), con una tolerancia + - 1
	*/
	public function setMontosTotales(MontosTotales $montosTotales)
	{
		$this->montosTotales = $montosTotales;

		return $this;
	}

	public function setItem(Item $item)
	{
		$this->item[] = $item;

		return $this;
	}

	private function prepareDocumento()
	{
		$this->debitNote->setID($this->serieCorrelativo)
										->setIssueDate($this->fechaEmision)
										->setIssueTime($this->horaEmision)
										->setNote($this->leyendas)
										->setDocumentCurrencyCode($this->tipoMoneda)
										->setDiscrepancyResponse($this->notaDebito)
										->setBillingReference($this->documentoModifica)
										->setDespatchDocumentReference($this->guias)																																																								
										->setAdditionalDocumentReference($this->otroDocumento)
										->setSignature($this->signature)
										->setAccountingSupplierParty($this->emisor)
										->setAccountingCustomerParty($this->cliente)
										->setTaxTotal($this->totalImpuestos, $this->subTotalImpuesto, false)
										->setRequestedMonetaryTotal($this->montosTotales)
										->setDebitNoteLine($this->item);

		return $this;
	}


	public function getDocumentoXml()
	{
		$this->prepareDocumento();

		return $this->debitNote->getXml();
	}
}