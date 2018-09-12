<?php

namespace Cpe;

use Sabre\Xml\XmlSerializable;

interface ElementInterface extends XmlSerializable
{
	const CBC 	= '{urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2}';
  const CAC 	= '{urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2}';
  const DS  	= '{http://www.w3.org/2000/09/xmldsig#}';
  const EXT 	= '{urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2}';
  const SAC 	= '{urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1}';
  const CCTS	= '{urn:un:unece:uncefact:documentation:2}';
  const QDT		= '{urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2}';
  const UDT		= '{urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2}';
  const XSI		= '{http://www.w3.org/2001/XMLSchema-instance}';
 	//add DVL
	const SOAPENV	= '{http://schemas.xmlsoap.org/soap/envelope/}';
	const SER		= '{http://service.sunat.gob.pe}';
	const WSSE	= '{http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd}';

	public function setSchema();
	public function setElement();
	public function setAttribute();
	public function setValue();
}
