<?php
namespace DBAL\SQL\Query;

use Util\Interfaces as I;

class Insert extends Container
{
    function __construct( $table, \Kinesis\Task $parent )
    {
        if( $table instanceof I\Nameable )
            $table = $table->getName();

        $params = array('Table' => $table );
        
        parent::__construct( $params, $parent );
    }
    
    function execute()
    {
        extract( $this->Parameters );
        
        $platform = $this->getPlatform();
        
        return $platform->insert( $Table ).parent::execute();
    }
}