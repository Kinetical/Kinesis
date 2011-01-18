<?php
namespace DBAL\Data\Filter;

class Scalar extends \DBAL\Query\Filter
{
    function execute( array $params = null )
    {
        $input = $params['input'];

        if( !is_array( $input ))
            throw new \DBAL\Exception('Scalar filter accepts array input, '.get_class($input).' provided.');

        while( is_array( $input ) )
        {
            $keys = array_keys( $input );
            $input = $input[ $keys[0] ];
            if( is_int( $keys[0] ) )
            {
                $output = $input;
                break;
            }
        }          

        return $output;
    }
}
