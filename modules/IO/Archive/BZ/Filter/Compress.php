<?php
namespace IO\Archive\BZ\Filter;

class Compress extends \IO\Filter
{
    function execute( array $params = array() )
    {
        $input = (string)$params['input'];

        $size = $params['size'];
        if( !is_int( $size ))
            $size = 4;

        $factor = $params['factor'];
        if( !is_int( $factor ))
            $factor = 0;

        return bzcompress( $input, $size, $factor );
    }
}