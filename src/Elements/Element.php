<?php

namespace Cpe\Elements;

use Cpe\ElementInterface;
use Sabre\Xml\Writer;

class Element implements ElementInterface
{
	private $schema = '';

	private $element = '';

	private $value = [];

	private $attributes = [];

	public function __construct($schema = '', $element = '')
	{
		$this->schema = $schema;
		$this->element = $element;

		return $this;
	}

	public function setSchema($schema = '')
	{
		$this->schema = $schema;

		return $this;
	}

	public function setElement($element = '')
	{
		$this->element = $element;

		return $this;
	}

	public function setAttribute($key = '', $value = '')
	{
		if($key != '' && $value != '')
		{
			$this->attributes[$key] = $value;
		}

		return $this;
	}

	public function setValue($value = '')
	{
		$this->value[] = $value;

		return $this;
	}

	public function xmlSerialize(Writer $write)
	{
		if($this->schema != '' && $this->element != '')
		{			
			$content['name'] = $this->schema . $this->element;
			$content['value'] = $this->value;
			
			if(count($this->attributes) > 0)
			{
				$content['attributes'] = $this->attributes;
			}

			$write->write([$content]);
		}
	}
}