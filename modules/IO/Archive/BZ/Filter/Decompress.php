<?php
namespace IO\Archive\BZ\Filter;

class Decompress extends \Core\Filter
{
    function execute( array $params = array() )
    {
        $input = (string)$params['input'];
        
        $quick = $params['quick'];
        if( !is_bool( $quick ))
            $quick = false;

        return bzdecompress( $input, $quick );
    }
}