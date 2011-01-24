<?php
namespace Core\Filter;

class Chain extends \Util\Collection\Iterator
{
    function register( \Core\Filter $filter )
    {
        $this->Keys[] = $filter->Type->getName( false );
        $this->Data[] = $filter;
    }

    protected function execute( array $params = array() )
    {
        return $this->current()->execute( $params );
    }

    function __invoke( array $params = array() )
    {
        return $this->execute( $params );
    }

    function hasFilter( $class )
    {
        return $this->offsetExists( $class );
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