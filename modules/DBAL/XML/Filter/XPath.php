<?php
namespace DBAL\XML\Filter;

class XPath extends \Core\Filter
{
    protected function execute( array $params = null )
    {
        $input = $params['input'];

        $xpath = $this->parameters['xpath'];

        if( method_exists( $input, 'xpath'))
        {
            $result = $input->xpath( $xpath );
            if( empty( $result ))
                return null;
            else
                return $result;
        }
        else
            throw new \DBAL\Exception('XPath filter input must implement xpath() method,'.get_class( $input ).' does not');
    }
}