<?php
namespace DBAL\Query;

class Result extends \Util\Collection\Dictionary
{
    private $_query;
    
    function __construct( \DBAL\Query $query )
    {
        $this->setQuery( $query );

        parent::__construct( array(), $query->getDataType() );
    }

    function getQuery()
    {
        return $this->_query;
    }

    protected function setQuery( \DBAL\Query $query )
    {
        $this->_query = $query;
    }
}