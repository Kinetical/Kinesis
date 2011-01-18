<?php
namespace DBAL\Query;

abstract class Filter extends \Core\Filter
{
    private $_query;

    function __construct( $query = null, array $params = null )
    {
        if( $query instanceof \DBAL\Query\Builder )
            $query = $query->getQuery();
        if( $query instanceof \DBAL\Query )
        {
            $query->Filters->register( $this );
            $this->_query = $query;
        }

        parent::__construct( $params );
    }

    function getQuery()
    {
        return $this->_query;
    }

    function setQuery( \DBAL\Query $query )
    {
        $this->_query = $query;
    }
}