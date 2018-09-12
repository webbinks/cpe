<?php

namespace Cpe\Components;

class Descuentos
{
	private $indicador;
	private $codigo;
	private $factorPorcentaje;
	private $importe;
	private $importeBase;

	public function setIndicador($indicador = '')
	{
		$this->indicador = $indicador;

		return $this;
	}

	public function setCodigo($codigo = '')
	{
		$this->codigo = $codigo;

		return $this;
	}

	public function setFactorPorcentaje($factorPorcentaje = '')
	{
		$this->factorPorcentaje = $factorPorcentaje;

		return $this;
	}

	public function setImporte($importe = '')
	{
		$this->importe = $importe;

		return $this;
	}

	public function setImporteBase($importeBase = '')
	{
		$this->importeBase = $importeBase;

		return $this;
	}

	public function getIndicador()
	{
		return $this->indicador;
	}

	public function getCodigo()
	{
		return $this->codigo;
	}

	public function getFactorPorcentaje()
	{
		return $this->factorPorcentaje;
	}

	public function getImporte()
	{
		return $this->importe;
	}

	public function getImporteBase()
	{
		return $this->importeBase;
	}
}