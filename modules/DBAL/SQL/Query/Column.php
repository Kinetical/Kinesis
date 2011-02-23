<?php
namespace DBAL\SQL\Query;

class Column extends Statement
{
    function __construct( $name, $type, $length = 0, $default = null, array $flags = null, \Kinesis\Task $parent )
    {
        $params = array( 'Name'   => $name,
                         'Type'   => $type,
                         'Length' => $length,
                         'Default'=> $default,
                         'Flags'  => $flags );
        
        parent::__construct( $params, $parent );
    }
    
    function execute()
    {
        extract( $this->Parameters );
        
        $platform = $this->getPlatform();
        
        return $platform->column( $Name, $Type, $Length, $Default, $Flags );
    }
}