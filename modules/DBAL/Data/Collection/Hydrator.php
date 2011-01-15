<?php
namespace DBAL\Data\Collection;

class Hydrator extends \DBAL\Hydrator
{
	function flush()
	{
		$items = array();
                $query = $this->getResource()->getQuery();
		if( $query->hasResult() )
			foreach( ($results = $query->getResults()) as $key => $value )
				$items[ $key ] = $this->load( $query->getName(), $value );

		return $items;
	}

	function load( $path, $result )
	{
		if( count( $result ) == 1 )
			return array_pop($result);

		return $result;
	}
}
