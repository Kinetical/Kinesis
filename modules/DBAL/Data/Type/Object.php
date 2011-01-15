<?php
namespace DBAL\Data\Type;

class Object extends String
{
	function toGeneric( $value )
	{
		return unserialize( $value );
	}
}