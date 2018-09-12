<?php

namespace Cpe;

use Sabre\Xml\XmlSerializable;
use Sabre\Xml\Writer;

interface ModelInterface extends XmlSerializable
{
	/*xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
	xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
	xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
	xmlns:ccts="urn:un:unece:uncefact:documentation:2"
	xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
	xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
	xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2"
	xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"*/

	const NAMESPACEMAP = ['urn:oasis:names:specification:ubl:schema:xsd:Invoice-2' => '',
                        'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2' => 'cac',
                        'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2' => 'cbc',
												'urn:un:unece:uncefact:documentation:2' => 'ccts',
                        'http://www.w3.org/2000/09/xmldsig#' => 'ds',
                        'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2' => 'ext',
												'urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2' => 'qdt',
                        //'urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1'=>'sac',
												'urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2' => 'udt',
												'http://www.w3.org/2001/XMLSchema-instance' => 'xsi'];

	public function setUBLExtensions();

	public function setUBLVersionID();

	public function setSignature($emisor);

	public function xmlSerialize(Writer $writer);

	public function getXml();
}
