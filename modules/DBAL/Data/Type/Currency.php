<?php
namespace DBAL\Data\Type;

class Currency extends \DBAL\Data\Type
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