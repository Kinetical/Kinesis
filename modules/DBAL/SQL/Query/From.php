<?php
namespace DBAL\SQL\Query;

class From extends Container
{
    function __construct( $table, $alias = '', \Kinesis\Task $parent  )
    {
        $params = array('Table' => $table,
                        'Alias' => $alias );
        parent::__construct( $params, $parent );
    }
    
    function initialise()
    {
        $table = $this->Parameters['Table'];
        $alias = $this->Parameters['Alias'];
        
        if( $table instanceof \DBAL\Data\Entity )
        {
            if( empty( $alias ) &&
                $table->hasAlias() )
                $this->Parameters['Alias'] = $table->getAlias();
            
            $this->Parameters['Table']= $table->getName();
        }
    }
    
    function execute()
    {
        extract( $this->Parameters );
        
        $platform = $this->getPlatform();
        $Table = $platform->identifier( $Table );
        $sql = $platform->from( $Table );
        if( !is_null( $Alias ))
            $sql.=$platform->alias( $Alias );
        
        return $sql;
        
    }
}