<?php
namespace Core\Type;

class IntegerType extends \DBAL\DataType
{
	function toBase()
	{
		return 'integer';
	}

	function toGeneric( $value )
	{
		return intval( $value );
	}
}