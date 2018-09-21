<?php

namespace Cpe\Comprobantes;

use Cpe\ComprobanteInterface;
use Cpe\Models\Invoice;
use Cpe\Components\Leyenda;
use Cpe\Components\Cliente;
use Cpe\Components\Emisor;
use Cpe\Components\Descuentos;
use Cpe\Components\SubTotalImpuesto;
use Cpe\Components\MontosTotales;
use Cpe\Components\Item;
use Cpe\Components\Detraccion;
use Cpe\Components\Anticipo;

class BoletaVenta implements ComprobanteInterface
{
	private $invoice;

	private $signature;

	private $tipoDocumento;

	private $tipoOperacion;

	private $serieCorrelativo;

	private $fechaEmision;

	private $horaEmision;

	private $fechaVencimiento;

	private $tipoMoneda;

	private $leyendas;

	private $numeroOrdenCompra;

	private $guias;

	private $otroDocumento;

	private $emisor;

	private $cliente;

	private $descuentos;

	private $totalImpuestos;

	private $subTotalImpuesto;

	private $montosTotales;

	private $numeroItem;

	private $item;

	private $detraccion;

	private $anticipo;

	public function __construct()
	{
		$this->invoice = new Invoice();

		$this->invoice->setUBLExtensions();
		$this->invoice->setUBLVersionID();
		$this->invoice->setCustomizacionID();
		$this->setTipoDocumento('03');

		return $this;
	}


	public function setTipoDocumento($tipoDocumento = '')
	{
		$this->tipoDocumento = $tipoDocumento;

		return $this;
	}

	public function setTipoOperacion($tipoOperacion = '')
	{
		$this->tipoOperacion = $tipoOperacion;

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

	public function setFechaVencimiento($fechaVencimiento = '')
	{
		$this->fechaVencimiento = $fechaVencimiento;

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

	public function setNumeroOrdenCompra($numeroOrdenCompra = '')
	{
		$this->numeroOrdenCompra = $numeroOrdenCompra;

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

	//ojo
	/*public function setLugarEntrega()
	{}*/

	/*
	Información de descuentos Globales
	*/
	public function setDescuentos(Descuentos $descuentos)
	{
		$this->descuentos = $descuentos;

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

	public function setImporteTotalVenta($importeTotalVenta = '')
	{
		$this->valoresTotales['importe_total_venta'] = $importeTotalVenta;

		return $this;
	}

	public function setNumeroItem($numeroItem = '')
	{
		$this->numeroItem = $numeroItem;

		return $this;
	}

	public function setItem(Item $item)
	{
		$this->item[] = $item;

		return $this;
	}

	public function setDetraccion(Detraccion $detraccion)
	{
		$this->detraccion = $detraccion;

		return $this;
	}

	public function setAnticipo(Anticipo $anticipo)
	{
		$this->anticipo = $anticipo;

		return $this;
	}

	private function prepareDocumento()
	{
		$this->invoice->setID($this->serieCorrelativo)
									->setIssueDate($this->fechaEmision)
									->setIssueTime($this->horaEmision)
									->setInvoiceTypeCode($this->tipoDocumento, $this->tipoOperacion)
									->setNote($this->leyendas)
									->setDocumentCurrencyCode($this->tipoMoneda)
									->setLineCountNumeric($this->numeroItem)
									//->setInvoicePeriod()
									->setOrderReference($this->numeroOrdenCompra)
									->setDespatchDocumentReference($this->guias)																																																								
									->setAdditionalDocumentReference($this->otroDocumento)
									->setAdditionalDocumentReference($this->anticipo, 2)
									->setSignature($this->signature)
									->setAccountingSupplierParty($this->emisor)
									->setAccountingCustomerParty($this->cliente)
									->setPaymentMeans($this->detraccion)
									->setPaymentTerms($this->detraccion)
									->setPrepaidPayment($this->anticipo)
									->setAllowancecharge($this->descuentos, false)
									->setTaxTotal($this->totalImpuestos, $this->subTotalImpuesto, false)
									->setLegalMonetaryTotal($this->montosTotales)
									->setInvoiceLine($this->item);

		return $this;
	}


	public function getDocumentoXml()
	{
		$this->prepareDocumento();

		return $this->invoice->getXml();
	}
}