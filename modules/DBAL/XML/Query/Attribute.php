<?php
namespace DBAL\XML\Query;

class Attribute extends \Kinesis\Task\Statement
{
    function __construct( $name, $value, $operator = null, \Kinesis\Task $parent = null )
    {
        if( is_null( $operator ))
            $operator = '=';
        
        $params = array('Name'      => $name,
                        'Value'     => $value,
                        'Operator'  => $operator);
        
        $parent->Children['Where']->addChild( $this );
        parent::__construct( $params, $parent->Children['Where'] );
    }
    
    function execute()
    {
        $params = $this->Parameters;
        
        if( strpos( $params['Operator'], '!') !== false )
        {
            $negate = true;
            $params['Operator'] = str_replace('!','',$params['Operator']);
        }
        
        $newPath= '@'.$params['Name'].$params['Operator']."'".$params['Value']."'";
         if( $negate )
             $newPath = 'not('.$newPath.')';
         
        return $newPath;
    }
}
