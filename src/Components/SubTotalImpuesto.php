<?php

namespace Cpe\Components;

class SubTotalImpuesto
{
	private $totalValor;
	private $importeTributo;
	private $codigoTributo;
	private $nombreTributo;
	private $codigoInternacionalTributo;

	private $tasaTributo;
	private $codigoAfectacionTributo;
	private $tipoSistemaTributo;

	public function setTotalValor($totalValor = '')
	{
		$this->totalValor = $totalValor;

		return $this;
	}

	public function setImporteTributo($importeTributo = '')
	{
		$this->importeTributo = $importeTributo;

		return $this;
	}

	public function setCodigoTributo($codigoTributo = '')
	{
		$this->codigoTributo = $codigoTributo;

		return $this;
	}

	public function setNombreTributo($nombreTributo = '')
	{
		$this->nombreTributo = $nombreTributo;

		return $this;
	}

	public function setCodigoInternacionalTributo($codigoInternacionalTributo = '')
	{
		$this->codigoInternacionalTributo = $codigoInternacionalTributo;

		return $this;
	}

	public function setTasaTributo($tasaTributo = '')
	{
		$this->tasaTributo = $tasaTributo;

		return $this;
	}

	public function setCodigoAfectacionTributo($codigoAfectacionTributo = '')
	{
		$this->codigoAfectacionTributo = $codigoAfectacionTributo;

		return $this;
	}

	public function setTipoSistemaTributo($tipoSistemaTributo = '')
	{
		$this->tipoSistemaTributo = $tipoSistemaTributo;

		return $this;
	}

	public function getTotalValor()
	{
		return $this->totalValor;
	}

	public function getImporteTributo()
	{
		return $this->importeTributo;
	}

	public function getCodigoTributo()
	{
		return $this->codigoTributo;
	}

	public function getNombreTributo()
	{
		return $this->nombreTributo;
	}

	public function getCodigoInternacionalTributo()
	{
		return $this->codigoInternacionalTributo;
	}

	public function getTasaTributo()
	{
		return $this->tasaTributo;
	}

	public function getCodigoAfectacionTributo()
	{
		return $this->codigoAfectacionTributo;
	}

	public function getTipoSistemaTributo()
	{
		return $this->tipoSistemaTributo;
	}
}