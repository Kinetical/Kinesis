<?php
namespace DBAL\XML\Filter;

class Node extends \DBAL\Query\Filter
{

    function execute( $input, $params = null )
    {
        if( !( $input instanceof \SimpleXMLElement ) )
            throw new \DBAL\Exception('Node filter input must be a SimpleXMLElement, '.get_class( $input ).' provided.');
        
        if( $params instanceof \DBAL\Data\Tree\Node )
            $parent = $params;
        else
            $parent = null;

        $attributes = $this->getAttributes( $input );
        $node = new \DBAL\Data\Tree\Node( $input->getName(), $attributes, $parent );
        $node->setValue( (string)$input );

        foreach( $input as $child )
            if( $child instanceof \SimpleXMLElement )
                $this->execute( $child, $node );

        return $node;
    }

    protected function getAttributes( \SimpleXMLElement $node )
    {
        if( count($node->attributes()) > 0 )
        {
                $attr = (array)$node->attributes();
                
                return $attr['@attributes'];
        }

        return array();
    }
}