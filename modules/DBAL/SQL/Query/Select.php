<?php
namespace DBAL\SQL\Query;

class Select extends Statement
{
    function __construct()
    {
        $params = array();
        if( func_num_args() > 0 )
        {
            $args = func_get_args();
            if( is_array( $args[0] ) )
                $params = $args[0];

            if( is_string( $args[0] ) )
            {
                if( strpos($args[0], ',' ) !== false )
                    $params = explode(',', $args[0]);
                else
                {
                    $parent = array_pop( $args );
                    $params = $args;
                }
            }
        }

        parent::__construct( $params, $parent );
    }
    function execute()
    {
        $platform = $this->getPlatform();
        
        return $platform->select( implode( $this->Parameters, ','));
    }
}