<?php
namespace DBAL\Data\Type;

class TimeStamp extends \DBAL\Data\Type
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