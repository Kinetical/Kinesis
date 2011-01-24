<?php
namespace IO\Archive\GZ\Filter;

class Inflate extends \Core\Filter
{
    function execute( array $params = array() )
    {
        $input = (string)$params['input'];

        $length = $params['length'];
        if( !is_int( $length ))
            $length = 0;

        return gzinflate( $input, $length );
    }
}