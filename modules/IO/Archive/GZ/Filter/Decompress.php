<?php
namespace IO\Archive\BZ\Filter;

class Decompress extends \IO\Filter
{
    function execute( array $params = array() )
    {
        $input = (string)$params['input'];

        $length = $params['length'];
        if( !is_int( $length ))
            $length = 0;

        return gzuncompress( $input, $length );
    }
}