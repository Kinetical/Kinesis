<?php
namespace DBAL\Data\Type;

class String extends \DBAL\Data\Type
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