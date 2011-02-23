<?php
namespace DBAL\SQL\Query;

class Tables extends Statement
{
    function __construct( \Kinesis\Task $parent )
    {
        parent::__construct( null, $parent );
    }
    
    function execute()
    {
        $platform = $this->getPlatform();
        
        return $platform->show().$platform->tables();
    }
}