<?php

namespace Cpe\Components;

class Cliente
{
	private $codigoDocumentoIdentidad;
	private $numeroDocumentoIdentidad;
	private $nombreComercial;
	private $razonSocial;
	private $codigoDomicilioFiscal;

	public function setCodigoDocumentoIdentidad($codigoDocumentoIdentidad = '')
	{
		$this->codigoDocumentoIdentidad = $codigoDocumentoIdentidad;

		return $this;
	}

	public function setNumeroDocumentoIdentidad($numeroDocumentoIdentidad = '')
	{
		$this->numeroDocumentoIdentidad = $numeroDocumentoIdentidad;

		return $this;
	}

	public function setNombreComercial($nombreComercial = '')
	{
		$this->nombreComercial = $nombreComercial;

		return $this;
	}

	public function setRazonSocial($razonSocial = '')
	{
		$this->razonSocial = $razonSocial;

		return $this;
	}

	public function setCodigoDomicilioFiscal($codigoDomicilioFiscal = '')
	{
		$this->codigoDomicilioFiscal = $codigoDomicilioFiscal;

		return $this;
	}

	public function getCodigoDocumentoIdentidad()
	{
		return $this->codigoDocumentoIdentidad;
	}

	public function getNumeroDocumentoIdentidad()
	{
		return $this->numeroDocumentoIdentidad;
	}

	public function getNombreComercial()
	{
		return $this->nombreComercial;
	}

	public function getRazonSocial()
	{
		return $this->razonSocial;
	}

	public function getCodigoDomicilioFiscal()
	{
		return $this->codigoDomicilioFiscal;
	}
}