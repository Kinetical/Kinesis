<?php
namespace Core\Type;

class CurrencyType extends \DBAL\DataType
{
	function toBase()
	{
		return 'decimal';
	}

	function toGeneric($value)
	{
		return floatval( $value );
	}
}