<?php
namespace DBAL\Query;

abstract class Filter extends \Core\Filter
{
    private $_query;

    function __construct( \DBAL\Query $query, array $params = null )
    {
        $query->Filters->register( $this );
        $this->_query = $query;

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