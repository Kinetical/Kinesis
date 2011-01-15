<?php
namespace DBAL\XML\Filter;

class SimpleXML extends \DBAL\Query\Filter
{
    function execute( $input, $params = null )
    {
        if( is_array( $params ))
            if( array_key_exists('ClassName', $params ))
                $className = $params['ClassName'];

        if( $className == null )
            $className = 'SimpleXMLIterator';

       return simplexml_load_string( $input, $className );
    }
}
