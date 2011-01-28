<?php
namespace DBAL\XML\Filter;

use DBAL\XML\Data\Tree;

class Node extends \Core\Filter
{
    protected function execute( array $params = null )
    {
        $input = $params['input'];
        
        if( !( $input instanceof \SimpleXMLElement ) )
            throw new \DBAL\Exception('Node filter input must be a SimpleXMLElement, '.get_class( $input ).' provided.');
        
        $parent = $params['parent'];

        $attributes = $this->getAttributes( $input );
        $node = new Tree\Node( $input->getName(), $attributes, $parent );
        $node->setValue( trim((string)$input) );

        foreach( $input as $child )
            if( $child instanceof \SimpleXMLElement )
                $this->execute( array( 'input' => $child, 'parent' => $node ) );

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