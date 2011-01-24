<?php
namespace IO\Archive\BZ\Filter;

class Deflate extends \Core\Filter
{
    function execute( array $params = array() )
    {
        $input = (string)$params['input'];

        $level = $params['level'];
        if( !is_int( $level ))
            $level = -1;

        return gzdeflate( $input, $level );
    }
}