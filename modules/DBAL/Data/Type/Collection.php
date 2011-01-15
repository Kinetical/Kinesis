<?php
namespace DBAL\Data\Type;

class Collection extends \DBAL\Data\Type
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