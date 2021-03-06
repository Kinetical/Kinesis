<?php
namespace DBAL\SQL\Query;

class Update extends Container
{
    function __construct( $table, \Kinesis\Task $parent )
    {
        parent::__construct( array('Table' => $table), $parent );
    }
    
    function initialise()
    {
        $table = $this->Parameters['Table'];
        if( $table instanceof \DBAL\Data\Entity )
        {
            $this->Parameters['Name'] = $table->getName();
            if( $table->hasAlias() )
                $this->Parameters['Alias'] = $table->getAlias();
        }
    }
    
    function execute()
    {
        extract( $this->Parameters );
        $platform = $this->getPlatform();
        
        $q = $platform->update( $Name );
        if( isset( $Alias ))
            $q .= $platform->alias( $Alias );
        
        $q .= parent::execute();
        
        return $q;
    }
    
}