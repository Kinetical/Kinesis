<?php
namespace IO\Archive\GZ\Filter;

class Compress extends \Core\Filter
{
    function execute( array $params = array() )
    {
        $input = (string)$params['input'];

        $level = $params['level'];
        if( !is_int( $level ))
            $level = -1;

        return gzcompress( $input, $level );
    }
}