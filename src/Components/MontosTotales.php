<?php

namespace Cpe\Components;

class MontosTotales
{
	private $totalDescuentos;
	private $totalOtrosCargos;
	private $importeTotal;
	private $totalValorVenta;
	private $totalPrecioVenta;
	private $montoRedondeoImporteTotal;
	private $montoPrepagado;

	public function setTotalDescuentos($totalDescuentos = '')
	{
		$this->totalDescuentos = $totalDescuentos;

		return $this;
	}

	public function setTotalOtrosCargos($totalOtrosCargos = '')
	{
		$this->totalOtrosCargos = $totalOtrosCargos;

		return $this;
	}

	public function setImporteTotal($importeTotal = '')
	{
		$this->importeTotal = $importeTotal;

		return $this;
	}

	public function setTotalValorVenta($totalValorVenta = '')
	{
		$this->totalValorVenta = $totalValorVenta;

		return $this;
	}

	public function setTotalPrecioVenta($totalPrecioVenta = '')
	{
		$this->totalPrecioVenta = $totalPrecioVenta;

		return $this;
	}

	public function setMontoRedondeoImporteTotal($montoRedondeoImporteTotal = '')
	{
		$this->montoRedondeoImporteTotal = $montoRedondeoImporteTotal;

		return $this;
	}

	public function setMontoPrepagado($montoPrepagado = '')
	{
		$this->montoPrepagado = $montoPrepagado;

		return $this;
	}

	public function getTotalDescuentos()
	{
		return $this->totalDescuentos;
	}

	public function getTotalOtrosCargos()
	{
		return $this->totalOtrosCargos;
	}

	public function getImporteTotal()
	{
		return $this->importeTotal;
	}

	public function getTotalValorVenta()
	{
		return $this->totalValorVenta;
	}

	public function getTotalPrecioVenta()
	{
		return $this->totalPrecioVenta;
	}

	public function getMontoRedondeoImporteTotal()
	{
		return $this->montoRedondeoImporteTotal;
	}

	public function getMontoPrepagado()
	{
		return $this->montoPrepagado;
	}
}