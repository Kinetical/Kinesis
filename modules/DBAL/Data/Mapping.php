<?php
namespace DBAL\Data;

class Mapping extends \Util\Collection
{
    protected $filter;

    function __construct( array $array = array(), \DBAL\Data\Mapping\Filter $filter )
    {
        $this->filter = $filter;

        parent::__construct( $array );
    }

    function offsetExists( $name )
    {
        if( parent::offsetExists( $name ) ||
            $this->filter->match( $name ) )
            return true;

        return false;
    }
}