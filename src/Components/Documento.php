<?php

namespace Cpe\Components;

class Documento
{
	private $numeroItem;

	private $tipoDocumento;

	private $serie;

	private $codigo;

	private $motivo;

	public function setNumeroItem($numeroItem = '')
	{
		$this->numeroItem = $numeroItem;

		return $this;
	}

	public function setTipoDocumento($tipoDocumento = '')
	{
		$this->tipoDocumento = $tipoDocumento;

		return $this;
	}

	public function setSerie($serie = '')
	{
		$this->serie = $serie;

		return $this;
	}

	public function setCodigo($codigo = '')
	{
		$this->codigo = $codigo;

		return $this;
	}

	public function setMotivo($motivo = '')
	{
		$this->motivo = $motivo;

		return $this;
	}

	//
	public function getNumeroItem()
	{
		return $this->numeroItem;
	}

	public function getTipoDocumento()
	{
		return $this->tipoDocumento;
	}

	public function getSerie()
	{
		return $this->serie;
	}

	public function getCodigo()
	{
		return $this->codigo;
	}

	public function getMotivo()
	{
		return $this->motivo;
	}
}