<?php
namespace DBAL\Query;

class Result extends \Core\Collection
{
    private $_query;
    
    function __construct( \DBAL\Query $query )
    {
        $this->setQuery( $query );

        parent::__construct( $query->getDataType() );
    }

    function getQuery()
    {
        return $this->_query;
    }

    protected function setQuery( \DBAL\Query $query )
    {
        $this->_query = $query;
    }

    function getIterator()
    {

        $wrapper = $this->_query->getWrapper();

        if( $wrapper instanceof \IO\Stream\Wrapper )
            return new \DBAL\Data\Iterator( $this->Data, $this->_query->getWrapper() );

        return parent::getIterator();
    }
}