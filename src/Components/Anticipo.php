<?php

namespace Cpe\Components;

class Anticipo
{
	private $identificadorPago;

	private $montoAnticipado;

	private $fechaPago;

	private $serieNumeroComprobante;

	private $tipoComprobanteAnticipado;

	private $tipoDocumentoEmisor;

	public $numeroDocumentoEmisor;

	public function setIdentificadorPago($identificadorPago = '')
	{
		$this->identificadorPago = $identificadorPago;

		return $this;
	}

	public function setMontoAnticipado($montoAnticipado = '')
	{
		$this->montoAnticipado = $montoAnticipado;

		return $this;
	}

	public function setFechaPago($fechaPago = '')
	{
		$this->fechaPago = $fechaPago;

		return $this;
	}

	public function setSerieNumeroComprobante($serieNumeroComprobante = '')
	{
		$this->serieNumeroComprobante = $serieNumeroComprobante;

		return $this;
	}

	public function setTipoComprobanteAnticipado($tipoComprobanteAnticipado = '')
	{
		$this->tipoComprobanteAnticipado = $tipoComprobanteAnticipado;

		return $this;
	}

	public function setTipoDocumentoEmisor($tipoDocumentoEmisor = '')
	{
		$this->tipoDocumentoEmisor = $tipoDocumentoEmisor;

		return $this;
	}

	public function setNumeroDocumentoEmisor($numeroDocumentoEmisor = '')
	{
		$this->numeroDocumentoEmisor = $numeroDocumentoEmisor;

		return $this;
	}

	public function getIdentificadorPago()
	{
		return $this->identificadorPago;
	}

	public function getMontoAnticipado()
	{
		return $this->montoAnticipado;
	}

	public function getFechaPago()
	{
		return $this->fechaPago;
	}

	public function getSerieNumeroComprobante()
	{
		return $this->serieNumeroComprobante;
	}

	public function getTipoComprobanteAnticipado()
	{
		return  $this->tipoComprobanteAnticipado;
	}

	public function getTipoDocumentoEmisor()
	{
		return $this->tipoDocumentoEmisor;
	}

	public function getNumeroDocumentoEmisor()
	{
		return $this->numeroDocumentoEmisor;
	}
}