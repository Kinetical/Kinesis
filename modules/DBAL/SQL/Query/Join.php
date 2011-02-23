<?php
namespace DBAL\SQL\Query;

class Join extends Statement
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
            
            $target = $this->getContainer()->getTable();
            $this->Parameters['TargetName'] = $target->getName();
            if( $target->hasAlias() )
                $this->Parameters['TargetAlias'] = $target->getAlias();
            $this->Parameters['TargetKey'] = $target->PrimaryKey->getName();
        }
    }
    
    function execute()
    {
        extract( $this->Parameters );
        
        $platform = $this->getPlatform();
        
        $join = $platform->identifier( $Target, $Alias );
        
        $query = $platform->join( $join );
        
        $lft = $platform->identifier( $TargetName, $Alias );
        $rgt = $platform->identifier( $TargetKey, $TargetAlias );
        
        $query .= $platform->on( $lft, $rgt );
        
        return $query;
        
    }
}