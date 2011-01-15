<?php
namespace DBAL\Data\Type;

class Integer extends \DBAL\Data\Type
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