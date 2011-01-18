<?php
namespace DBAL\XML\Text;

class Writer extends \IO\Text\Writer
{

    private $_stack = array();
    private $_indent = 0;

    function writeDocument( \DBAL\XML\Document $document )
    {
        $node = $document->getRoot();

        $this->writeDocumentHeader( $document );
        $this->writeNode( $node );
    }

    function writeDocumentHeader(\DBAL\XML\Document $document)
    {
        $this->writeLine( '<?xml version="'.$document->getVersion().'" encoding="'.$document->getEncoding().'"?>' );
    }

    function writeNode( \DBAL\Data\Tree\Node $node )
    {
        $this->writeStartNode( $node );

        if( $node->hasChildren() )
            $this->writeNodes( $node->Children->toArray() );
        else
            $this->writeLine( $node->getValue() );

        $this->writeEndNode();
    }

    function writeNodes( $nodes )
    {
        if( !is_array( $nodes ))
            $nodes = array( $nodes );
        foreach( $nodes as $node )
        {
            if( $node instanceof \DBAL\Data\Tree\Node )
                $this->writeNode( $node );
        }
    }

    function writeStartNode( \DBAL\Data\Tree\Node $node )
    {
        $element = $node->getName();
        

        array_push( $this->_stack, $element );

        $this->writeIndent();
        $this->write( '<'.$element );

        if( $node->hasAttributes() )
            $this->writeAttributes( $node->Attributes );

        $this->write('>');
        $this->writeLine();

        $this->_indent++;
    }

    function writeEndNode()
    {
        $element = array_pop( $this->_stack );

        $this->_indent--;
        $this->writeLine( '</'.$element.'>' );

        
    }

    function writeAttributes( $attributes )
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

    function writeLine( $data = null )
    {
        $this->writeIndent();

        parent::writeLine( $data );
    }

    function writeIndent()
    {
        $this->write( str_repeat("\t", $this->_indent ));
    }
}