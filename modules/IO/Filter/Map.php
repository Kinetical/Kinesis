<?php
namespace IO\Filter;

class Map extends \Util\Collection\Iterator
{   
    function register( \IO\Filter $filter )
    {
        $c = count( $this->Keys );
        $this->Keys[$c] = $filter->getName();
        $this->Data[$c] = $filter;
    }

    function recurse( \IO\Filter $filter, array $params = array() )
    {
        $this->register( new \IO\Filter\Recursive( $filter, $params ));
    }

    protected function execute( array $params = array() )
    {
        return $this->current()->execute( $params );
    }

    function __invoke( $params = null )
    {
        if( !is_null( $params ) &&
            !is_array( $params ))
            $params = array( 'input' => func_get_args() );

        return $this->execute( $params );
    }

    function hasFilter( $class )
    {
        return $this->offsetExists( $class );
    }

    function getFilters()
    {
        return $this->Data;
    }

    function setFilters( array $filters )
    {
        array_walk( $filters, array( $this, 'register' ));
    }
}