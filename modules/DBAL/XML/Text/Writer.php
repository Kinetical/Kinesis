<?php
namespace DBAL\XML\Text;

class Writer extends \IO\Text\Writer
{
    private $_stack = array();
    private $_indent = 0;

    function writeDocument( $node )
    {
        if( $node instanceof \DBAL\XML\Document )
            $node = $node->getRoot();

        if( !($node instanceof \DBAL\Data\Tree\Node ))
            throw new DBAL\Exception('XML\Text\Writer can only write a Data\Tree\Node, '.get_class( $node ).' provided');

        $this->writeDocumentHeader();
        $this->writeNode( $node );
    }

    function writeDocumentHeader( $version = '1.0', $encoding = 'UTF-8')
    {
        $this->writeLine( '<?xml version="'.$version.'" encoding="'.$encoding.'"?>' );
    }

    function writeNode( \DBAL\Data\Tree\Node $node )
    {
        $this->writeStartNode( $node->getName(), $node->Attributes->toArray() );

        if( $node->hasChildren() )
            $this->writeNodes( $node->Children->toArray() );
        else
            $this->writeLine( $node->getValue() );

        $this->writeEndNode();
    }

    function writeNodes( array $nodes )
    {
        foreach( $nodes as $node )
        {
            if( $node instanceof \DBAL\Data\Tree\Node )
                $this->writeNode( $node );
        }
    }

    function writeStartNode( $element, array $attributes = null )
    {
        array_push( $this->_stack, $element );

        $this->writeIndent();
        $this->write( '<'.$element );

        if( is_array( $attributes ))
            $this->writeAttributes( $attributes );

        $this->writeLine('>');

        $this->_indent++;
    }

    function writeEndNode()
    {
        $element = array_pop( $this->_stack );

        $this->writeLine( '</'.$element.'>' );

        $this->_index--;
    }

    function writeAttributes( array $attributes )
    {
        foreach( $attributes as $name => $value )
            $this->writeAttribute( $name, $value );
    }

    function writeAttribute( $name, $value = null )
    {
        $this->write(' '.$name.'="'.$value.'"');
    }

    function writeStartComment()
    {
        $this->writeLine('<!--');
    }

    function writeEndComment()
    {
        $this->writeLine('-->');
    }

    function writeComment( $comment )
    {
        $this->writeStartComment();

        $this->writeLine( $comment );

        $this->writeEndComment();
    }

    function writeLine( $data )
    {
        $this->writeIndent();

        parent::writeLine( $data );
    }

    function writeIndent()
    {
        $this->write( str_repeat("\t", $this->_indent ));
    }
}