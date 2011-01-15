<?php
namespace DBAL\XML\Filter;

class XPath extends \DBAL\Query\Filter
{
    function execute( $input, $params = null )
    {
        $XPath = $this->Parameters['XPath'];

        if( method_exists( $input, 'xpath'))
            return $input->xpath( $XPath );
        else
            throw new \DBAL\Exception('XPath input is not a valid SimpleXMLElement');
    }
}