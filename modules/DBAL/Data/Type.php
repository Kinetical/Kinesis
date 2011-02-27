<?php
namespace DBAL\Data;

abstract class Type extends \Kinesis\Object
{
	protected $_name;

	function __construct()
	{
		$this->_name = $this->toBase();
                parent::__construct();
	}

	function __toString()
	{
		return $this->_name;
	}

	abstract function toBase();
	abstract function toGeneric( $value );

	function getBase( &$result = null )
	{
		if( get_class() !== 'DataType')
		{
			if( $result == null )
				$result = array();

			$result[] = $this->toBase();

			return parent::getBase( $result );
		}

		return $result;
	}

	function getName()
	{
		return $this->_name;
	}

	protected function setName( $name )
	{
		$this->_name = $name;
	}
}
