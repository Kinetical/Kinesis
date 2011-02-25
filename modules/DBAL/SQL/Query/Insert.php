<?php
namespace DBAL\SQL\Query;

use Util\Interfaces as I;

class Insert extends Container
{
    function __construct( $table, \Kinesis\Task $parent )
    {
        $params = array('Table' => $table );
        
        parent::__construct( $params, $parent );
    }
    
    function initialise()
    {
        $table = $this->Parameters['Table'];
        
        if( $table instanceof \DBAL\Data\Entity )
            $this->Parameters['Name'] = $table->getName();
        elseif( is_string( $table ))
            $this->Parameters['Name'] = $table;
    }
    
    function execute()
    {
        extract( $this->Parameters );
        
        $platform = $this->getPlatform();
        
        return $platform->insert( $Name ).parent::execute();
    }
}