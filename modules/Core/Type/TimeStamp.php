<?php
namespace Core\Type;

class TimeStampType extends \DBAL\DataType
{
	function toBase()
	{
		return 'timestamp';
	}

	function toGeneric( $value )
	{
		return (string)$value;
	}
}