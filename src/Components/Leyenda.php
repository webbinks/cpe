<?php

namespace Cpe\Components;

class Leyenda
{
	private $codigo;
	private $descripcion;

	public function setCodigo($codigo = '')
	{
		$this->codigo = $codigo;

		return $this;
	}

	public function setDescripcion($descripcion = '')
	{
		$this->descripcion = $descripcion;

		return $this;
	}

	public function getCodigo()
	{
		return $this->codigo;
	}

	public function getDescripcion()
	{
		return $this->descripcion;
	}
}