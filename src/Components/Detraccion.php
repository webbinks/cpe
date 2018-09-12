<?php

namespace Cpe\Components;

class Detraccion
{
	private $tipoMoneda = 'PEN';

	private $codigo;

	private $nroCuentaBancaria;

	private $porcentaje;

	private $monto;

	// Recursos hidrobiológicos
	private $matriculaEmbarcacionPesquera = ['codigo' => '3001', 'concepto' => 'Detracciones: Recursos Hidrobiológicos-Matrícula de la embarcación', 'valor' => ''];
	private $nombreEmbarcacionPesquera = ['codigo' => '3002', 'concepto' => 'Detracciones: Recursos Hidrobiológicos-Nombre de la embarcación', 'valor' => ''];
	private $tipoEspecieVendida = ['codigo' => '3003', 'concepto' => 'Detracciones: Recursos Hidrobiológicos-Tipo de especie vendida', 'valor' => ''];
	private $lugarDescarga = ['codigo' => '3004', 'concepto' => 'Detracciones: Recursos Hidrobiológicos-Lugar de descarga', 'valor' => ''];
	private $fechaDescarga = ['codigo' => '3005', 'concepto' => 'Detracciones: Recursos Hidrobiológicos-Fecha de descarga', 'valor' => ''];
	private $cantidadEspecieVendida = ['codigo' => '3006', 'concepto' => 'Detracciones: Recursos Hidrobiológicos-Cantidad de especie vendida', 'valor' => ''];

	// Servicio de transporte de mercadería
	private $ubigeoPuntoOrigen;
	private $direccionPuntoOrigen;
	private $ubigeoPuntoDestino;
	private $direccionPuntoDestino;
	private $detalleViaje;
	private $valRefServicioTransporte;
	private $valRefCargaEfectiva;
	private $valRefCargaUtil;

	//Configuracíón vehicular
	private $configuracionVehicular;
	private $cargaUtilVehiculo;
	private $cargaEfectivaVehiculo;
	private $valorReferencial;

	public function setCodigo($codigo = '')
	{
		$this->codigo = $codigo;

		return $this;
	}

	public function setNroCuentaBancaria($nroCuentaBancaria = '')
	{
		$this->nroCuentaBancaria = $nroCuentaBancaria;

		return $this;
	}

	public function setPorcentaje($porcentaje = '')
	{
		$this->porcentaje = $porcentaje;

		return $this;
	}

	public function setMonto($monto = '')
	{
		$this->monto = $monto;

		return $this;
	}

	public function getTipoMoneda()
	{
		return $this->tipoMoneda;
	}


	public function getCodigo()
	{
		return $this->codigo;
	}

	public function getNroCuentaBancaria()
	{
		return $this->nroCuentaBancaria;
	}

	public function getPorcentaje()
	{
		return $this->porcentaje;
	}

	public function getMonto()
	{
		return $this->monto;
	}

	//ini recursos hidrobioligicos
	public function setMatriculaEmbarcacionPesquera($matricula = '')
	{
		$this->matriculaEmbarcacionPesquera['valor'] = $matricula;

		return $this;
	}

	public function setNombreEmbarcacionPesquera($nombre = '')
	{
		$this->nombreEmbarcacionPesquera['valor'] = $nombre;

		return $this;
	}

	public function setTipoEspecieVendida($especie = '')
	{
		$this->tipoEspecieVendida['valor'] = $especie;

		return $this;
	}

	public function setLugarDescarga($lugar = '')
	{
		$this->lugarDescarga['valor'] = $lugar;

		return $this;
	}

	public function setFechaDescarga($fecha = '')
	{
		$this->fechaDescarga['valor'] = $fecha;

		return $this;
	}

	public function setCantidadEspecieVendida($cantidad = '')
	{
		$this->cantidadEspecieVendida['valor'] = $cantidad;

		return $this;
	}

	public function getMatriculaEmbarcacionPesquera($key = '')
	{
		return $this->matriculaEmbarcacionPesquera[$key];
	}

	public function getNombreEmbarcacionPesquera($key = '')
	{
		return $this->nombreEmbarcacionPesquera[$key];
	}

	public function getTipoEspecieVendida($key = '')
	{
		return $this->tipoEspecieVendida[$key];
	}

	public function getLugarDescarga($key = '')
	{
		return $this->lugarDescarga[$key];
	}

	public function getFechaDescarga($key = '')
	{
		return $this->fechaDescarga[$key];
	}

	public function getCantidadEspecieVendida($key = '')
	{
		return $this->cantidadEspecieVendida[$key];
	}
	//fin recursos hidrobiologicos

	//ini Servicio de transporte de carga
	public function setUbigeoPuntoOrigen($ubigeoPuntoOrigen = '')
	{
		$this->ubigeoPuntoOrigen = $ubigeoPuntoOrigen;

		return $this;
	}

	public function setDireccionPuntoOrigen($direccionPuntoOrigen = '')
	{
		$this->direccionPuntoOrigen = $direccionPuntoOrigen;

		return $this;
	}

	public function setUbigeoPuntoDestino($ubigeoPuntoDestino = '')
	{
		$this->ubigeoPuntoDestino = $ubigeoPuntoDestino;

		return $this;
	}

	public function setDireccionPuntoDestino($direccionPuntoDestino = '')
	{
		$this->direccionPuntoDestino = $direccionPuntoDestino;

		return $this;
	}

	public function setDetalleViaje($detalleViaje = '')
	{
		$this->detalleViaje = $detalleViaje;

		return $this;
	}

	public function setValRefServicioTransporte($valRefServicioTransporte = '')
	{
		$this->valRefServicioTransporte = $valRefServicioTransporte;

		return $this;
	}

	public function setValRefCargaEfectiva($valRefCargaEfectiva = '')
	{
		$this->valRefCargaEfectiva = $valRefCargaEfectiva;

		return $this;
	}

	public function setValRefCargaUtil($valRefCargaUtil = '')
	{
		$this->valRefCargaUtil = $valRefCargaUtil;

		return $this;
	}

	public function getUbigeoPuntoOrigen()
	{
		return $this->ubigeoPuntoOrigen;
	}

	public function getDireccionPuntoOrigen()
	{
		return $this->direccionPuntoOrigen;
	}

	public function getUbigeoPuntoDestino()
	{
		return $this->ubigeoPuntoDestino;
	}

	public function getDireccionPuntoDestino()
	{
		return $this->direccionPuntoDestino;
	}

	public function getDetalleViaje()
	{
		return $this->detalleViaje;
	}

	public function getValRefServicioTransporte()
	{
		return $this->valRefServicioTransporte;
	}

	public function getValRefCargaEfectiva()
	{
		return $this->valRefCargaEfectiva;
	}

	public function getValRefCargaUtil()
	{
		return $this->valRefCargaUtil;
	}
	//fin Servicio de transporte de carga

	//ini Configuracíón vehicular
	public function setConfiguracionVehicular($configuracionVehicular = '')
	{
		$this->configuracionVehicular = $configuracionVehicular;

		return $this;
	}

	public function setCargaUtilVehiculo($cargaUtilVehiculo = '')
	{
		$this->cargaUtilVehiculo = $cargaUtilVehiculo;

		return $this;
	}

	public function setCargaEfectivaVehiculo($cargaEfectivaVehiculo = '')
	{
		$this->cargaEfectivaVehiculo = $cargaEfectivaVehiculo;

		return $this;
	}

	public function setValorReferencial($valorReferencial = '')
	{
		$this->valorReferencial = $valorReferencial;

		return $this;
	}

	public function getConfiguracionVehicular()
	{
		return $this->configuracionVehicular;
	}

	public function getCargaUtilVehiculo()
	{
		return $this->cargaUtilVehiculo;
	}

	public function getCargaEfectivaVehiculo()
	{
		return $this->cargaEfectivaVehiculo;
	}

	public function getValorReferencial()
	{
		return $this->valorReferencial;
	}
	//fin Configuracíón vehicular
}