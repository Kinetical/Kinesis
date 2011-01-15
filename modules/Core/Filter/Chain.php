<?php
namespace Core\Filter;

class Chain extends \Core\Iterator
{
    function register( \Core\Filter $filter )
    {
        $this->keys[] = $filter->Type->name;
        $this->Data[] = $filter;
    }

    function execute( $input = null, array $params = null )
    {
        return $this->current()->execute( $input, $params );
    }

    function hasFilter( $class )
    {
        return in_array( $class, $this->keys );
    }

    protected function getFilters()
    {
        return $this->getArray();
    }

    protected function setFilters( array $filters )
    {
        $this->setArray( $filters );
    }
}