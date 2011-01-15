<?php
namespace Core\Type;

class ArrayType extends \DBAL\DataType
{
	function toBase()
	{
		return 'array';
	}

	function toGeneric( $value )
	{
		return (array)$value;
	}
}