<?php
namespace Core\Type;

class StringType extends \DBAL\DataType
{
	function toBase()
	{
		return 'string';
	}

	function toGeneric( $value )
	{
		return (string)$value;
	}
}