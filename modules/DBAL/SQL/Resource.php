<?php
namespace DBAL\SQL;

class Resource extends \IO\Resource
{
	function flush()
	{
            $stream = $this->getStream();

            if( !$stream->isOpen() )
		throw new \Core\Exception('Connection  unavailable');

            $query = $this->getQuery();

            $results = $query->setResults( $stream->getMode()->execute( (string)$query  ) );

               
            if( !is_bool( $query->getResults() ))
            {
                $results = $query->setResults( parent::flush());
                //var_dump( $this->Query->Results );
                $items = $this->getLoader()->flush();
                return $items;
            }
            else
                return null;
	}

	function next()
	{
            $this->increment();
            $this->setBuffer( null );

            $buffer = $this->setBuffer( mysql_fetch_assoc( $this->getQuery()->getResults() ));

            return $buffer;
	}

	

	function getDefaultHydrator()
	{
            $query = $this->getQuery();
            $hydration = $query->getHydration();
            $dataType = $query->getDataType();

            if( $hydration instanceof AbstractHydrator )
            {
                $hydration->setResource( $this );
                $hydration->Type = $dataType;
                return $hydration;
            }
            elseif( $hydration == Query::HYDRATE_OBJECT )
                return new Hydrator\ObjectHydrator ( $this, $dataType );
            elseif( $hydration == Query::HYDRATE_ARRAY )
                return new Hydrator\ArrayHydrator( $this );
            elseif( $hydration == Query::HYDRATE_TREE )
                return new Hydrator\NodeHydrator( $this );
            elseif( $hydration == Query::HYDRATE_NESTEDSET )
                return new Hydrator\NestedSetHydrator( $this, $dataType );
            elseif( $hydration == Query::HYDRATE_SCALAR )
                return new Hydrator\ScalarHydrator( $this, $dataType );
            elseif( is_string( $dataType ) )
                return new Hydrator\ObjectHydrator( $this, $dataType );
	}
}