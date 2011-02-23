<?php
namespace DBAL\SQL\Query;

class Container extends Statement
{
    function __construct( array $params, \Kinesis\Task $parent )
    {
        $parent->Parameters['Container'] = $this;
        
        parent::__construct( $params, $parent );
    }
    
    function getTable()
    {
        return $this->Parameters['Table'];
    }
}