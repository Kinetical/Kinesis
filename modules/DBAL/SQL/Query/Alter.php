<?php
namespace DBAL\SQL\Query;

use Util\Interfaces as I;

class Alter extends Container
{
    function __construct( $table, \Kinesis\Task $parent = null )
    {
        if( $table instanceof I\Nameable )
            $table = $table->getName();
        
        parent::__construct( array('Table' => $table ), $parent );
    }
    
    function execute()
    {
        $platform = $this->getPlatform();
        
        return $platform->alter( $this->Parameters['Table'] ).
               parent::execute();
    }
}