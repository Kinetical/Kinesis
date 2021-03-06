<?php
namespace DBAL\XML\Data\Tree;

use IO\Output;
use DBAL\XML;

class Node extends \DBAL\Data\Tree\Node
{
    private $_element;

    protected function getElement()
    {
        if( is_null( $this->_element ))
            $this->_element = $this->toElement();
        
        return $this->_element;
    }

    protected function setElement( $element )
    {
        $this->_element = $element;
    }

    function toElement()
    {
        $stream = new Output\Stream();

        $writer = new XML\Text\Writer( new Output\Writer( $stream ) );
        $writer->writeNode( $this );

        $filter = new XML\Filter\SimpleXML();

        return $filter( (string)$stream );
    }

    function xpath( $xpath )
    {
        $element = $this->getElement();
        $nodes = $element->xpath( $xpath );

        if( !is_null( $nodes ))
        {
            if( !is_array( $nodes ))
                $nodes = array( $nodes );

            $filter = new \IO\Filter\Recursive( new XML\Filter\Node() );

            $results = array_map( $filter, $nodes );

            if( count($results) == 0 )
                return $results[0];

            return $results;
        }
        
        unset( $this->_element );

        return false;
    }

    function __invoke( $xpath )
    {
        return $this->xpath( $xpath );
    }
}
