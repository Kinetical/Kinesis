<?php
namespace DBAL\SQL\Query;

class Where extends Statement
{
    function __construct( $name, $value, $operator = null, \Kinesis\Task $parent = null )
    {
        if( is_null( $operator ))
            $operator = '=';
        
        $params = array( 'Name'     => $name,
                         'Value'    => $value,
                         'Operator' => $operator );

        parent::__construct( $params, $parent );
    }
    function execute()
    {
        extract( $this->Parameters );
        
        $platform = $this->getPlatform();
        $Name = $platform->identifier( $Name );
        return $platform->where( $Name, $Value, $Operator );
    }
}