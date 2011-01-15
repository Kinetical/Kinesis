<?php
namespace DBAL\Data\Scalar;

class Hydrator extends \Core\Object\Hydrator
{
	function flush()
	{
		$items = parent::flush();
		while( is_array( $items ))
		{
			$keys = array_keys( $items );
			$items = $items[ $keys[0] ];
			if( is_int( $keys[0] ) )
				break;
		}

		return $items;
	}
}