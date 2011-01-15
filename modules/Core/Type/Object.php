<?php
namespace Core\Type;

class ObjectType extends StringType
{
	function toGeneric( $value )
	{
		return unserialize( $value );
	}
}