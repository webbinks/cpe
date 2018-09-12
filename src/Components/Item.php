<?php

namespace Cpe\Components;

use Descuentos;

use Detraccion;

class Item
{
	private $numeroItem;

	private $codigoUnidadMedida;

	private $cantidad;

	private $codigoProducto;

	private $codigoProductoSunat;

	private $codigoProductoGS;

	private $gastosRenta;

	private $codigoGastosRenta;

	private $numeroPlacaVehiculo;

	private $descripcion;

	private $valorUnitario;

	private $precioVentaUnitario;

	private $codigoTipoPrecio;

	private $montoTotal;

	private $taxSubTotal;

	private $valorVenta;

	private $descuentos;

	private $detraccion;

	public function setNumeroItem($numeroItem = '')
	{
		$this->numeroItem = $numeroItem;

		return $this;
	}

	public function setCodigoUnidadMedida($codigoUnidadMedida = '')
	{
		$this->codigoUnidadMedida = $codigoUnidadMedida;

		return $this;
	}

	public function setCantidad($cantidad = '')
	{
		$this->cantidad = $cantidad;

		return $this;
	}

	public function setCodigoProducto($codigoProducto = '')
	{
		$this->codigoProducto = $codigoProducto;

		return $this;
	}

	public function setCodigoProductoSunat($codigoProductoSunat = '')
	{
		$this->codigoProductoSunat = $codigoProductoSunat;

		return $this;
	}

	public function setCodigoProductoGS($codigoProductoGS = '')
	{
		$this->codigoProductoGS = $codigoProductoGS;

		return $this;
	}

	public function setGastosRenta($gastosRenta = '')
	{
		$this->gastosRenta = $gastosRenta;

		return $this;
	}

	public function setCodigoGastosRenta($codigoGastosRenta = '')
	{
		$this->codigoGastosRenta = $codigoGastosRenta;

		return $this;
	}

	public function setNumeroPlacaVehiculo($numeroPlacaVehiculo = '')
	{
		$this->numeroPlacaVehiculo = $numeroPlacaVehiculo;

		return $this;
	}

	public function setDescripcion($descripcion = '')
	{
		$this->descripcion = $descripcion;

		return $this;
	}

	public function setValorUnitario($valorUnitario = '')
	{
		$this->valorUnitario = $valorUnitario;

		return $this;
	}

	public function setPrecioVentaUnitario($precioVentaUnitario = '')
	{
		$this->precioVentaUnitario = $precioVentaUnitario;

		return $this;
	}

	public function setCodigoTipoPrecio($codigoTipoPrecio = '')
	{
		$this->codigoTipoPrecio = $codigoTipoPrecio;

		return $this;
	}

	public function setMontoTotal($montoTotal = '')
	{
		$this->montoTotal = $montoTotal;

		return $this;
	}

	public function setTaxSubTotal($taxSubTotal = '')
	{
		$this->taxSubTotal[] = $taxSubTotal;

		return $this;
	}

	public function setValorVenta($valorVenta = '')
	{
		$this->valorVenta = $valorVenta;

		return $this;
	}

	public function setDescuentos($descuentos)
	{
		$this->descuentos = $descuentos;

		return $this;
	}

	public function setDetraccion($detraccion)
	{
		$this->detraccion = $detraccion;

		return $this;
	}

	//
	public function getNumeroItem()
	{
		return $this->numeroItem;
	}

	public function getCodigoUnidadMedida()
	{
		return $this->codigoUnidadMedida;
	}

	public function getCantidad()
	{
		return $this->cantidad;
	}

	public function getCodigoProducto()
	{
		return $this->codigoProducto;
	}

	public function getCodigoProductoSunat()
	{
		return $this->codigoProductoSunat;
	}

	public function getCodigoProductoGS()
	{
		return $this->codigoProductoGS;
	}

	public function getGastosRenta()
	{
		return $this->gastosRenta;
	}

	public function getCodigoGastosRenta()
	{
		return $this->codigoGastosRenta;
	}

	public function getNumeroPlacaVehiculo()
	{
		return $this->numeroPlacaVehiculo;
	}

	public function getDescripcion()
	{
		return $this->descripcion;
	}

	public function getValorUnitario()
	{
		return $this->valorUnitario;
	}

	public function getPrecioVentaUnitario()
	{
		return $this->precioVentaUnitario;
	}

	public function getCodigoTipoPrecio()
	{
		return $this->codigoTipoPrecio;
	}

	public function getMontoTotal()
	{
		return $this->montoTotal;
	}

	public function getTaxSubTotal()
	{
		return $this->taxSubTotal;
	}

	public function getValorVenta()
	{
		return $this->valorVenta;
	}

	public function getDescuentos()
	{
		return $this->descuentos;
	}

	public function getDetraccion()
	{
		return $this->detraccion;
	}
}