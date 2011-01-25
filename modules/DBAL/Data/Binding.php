<?php
namespace DBAL\Data;

class Binding extends Mapping
{

    function __construct( array $array = array(), \DBAL\Data\Mapping\Filter $filter )
    {
        parent::__construct( $array, $filter );
    }

    protected function parseOffset( $offset )
    {
        if( $offset instanceof \Core\Object )
            $offset = $offset->Oid;

        return $offset;
    }

    function offsetSet( $offset, $value )
    {
        parent::offsetSet( $this->parseOffset( $offset ), $value );
    }

    function offsetExists( $offset )
    {
        parent::offsetExists( $this->parseOffset( $offset ) );
    }

    function offsetGet( $offset )
    {
        return parent::offsetGet( $this->parseOffset( $offset ) );
    }

    function offsetUnset( $offset )
    {
        parent::offsetUnset( $this->parseOffset( $offset ) );
    }
}