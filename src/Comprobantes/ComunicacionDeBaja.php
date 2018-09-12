<?php

namespace Cpe\Comprobantes;

use Cpe\ComprobanteInterface;
use Cpe\Models\VoidedDocuments;
use Cpe\Components\Emisor;
use Cpe\Components\Documento;

class ComunicacionDeBaja implements ComprobanteInterface
{
	private $VoidedDocuments;

	private $signature;

	private $serieCorrelativo;

	private $fechaEmision;

	private $fechaGeneracion;

	private $emisor;

	private $documento;

	public function __construct()
	{
		$this->VoidedDocuments = new VoidedDocuments();

		$this->VoidedDocuments->setUBLExtensions();
		$this->VoidedDocuments->setUBLVersionID();
		$this->VoidedDocuments->setCustomizacionID();

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

	public function setFechaGeneracion($fechaGeneracion = '')
	{
		$this->fechaGeneracion = $fechaGeneracion;

		return $this;
	}

	public function setEmisor(Emisor $emisor)
	{
		$this->emisor = $emisor;
		$this->signature = $emisor;

		return $this;
	}

	public function setDocumento(Documento $documento)
	{
		$this->documento[] = $documento;

		return $this;
	}

	private function prepareDocumento()
	{
		$this->VoidedDocuments->setID($this->serieCorrelativo)
													->setReferenceDate($this->fechaGeneracion)
													->setIssueDate($this->fechaEmision)
													->setSignature($this->signature)
													->setAccountingSupplierParty($this->emisor)
													->setVoidedDocumentsLine($this->documento);

		return $this;
	}


	public function getDocumentoXml()
	{
		$this->prepareDocumento();

		return $this->VoidedDocuments->getXml();
	}
}