<?php
namespace IO\Stream;

abstract class Handler extends \Core\Object
{
    protected $stream;
    protected $buffer;
    protected $handler;


    function __construct()
    {
        if( count( $args = func_get_args() ) > 0 )
            foreach( $args as $arg )
                if( $arg instanceof \IO\Stream )
                    $this->setStream( $arg );
                elseif( $arg instanceof \IO\Stream\Handler )
                    $this->setHandler( $arg );
        
        parent::__construct();
    }

    protected function isOpen()
    {
        return $this->stream->isOpen();
    }

    protected function getBuffer()
    {
        return $this->buffer;
    }

    protected function setBuffer( $buffer )
    {
        $this->buffer = $buffer;
    }

    protected function buffered()
    {
        return !is_null( $this->buffer )
                ? true
                : false;
    }

    protected function clear()
    {
        unset($this->buffer);
    }

    function getStream()
    {
        if( is_null( $this->stream )
            && $this->wrapped() )
            $this->stream = $this->handler->getStream();

        return $this->stream;
    }

    function setStream( \IO\Stream $stream )
    {
        if( $this->wrapped() )
            return $this->handler->setStream( $stream );

        $this->stream = $stream;
    }

    protected function getHandler()
    {
        return $this->handler;
    }

    protected function setHandler( $handler )
    {
        if( is_null( $this->stream ) )
            $this->setStream( $handler->getStream() );
        
        $this->handler = $handler;
    }

    function wrapped()
    {
        return ( $this->handler instanceof \IO\Stream\Handler );
    }

    function getEncoding()
    {
        return $this->stream->getEncoding();
    }

    function setEncoding( $encoding )
    {
        $this->stream->setEncoding( $encoding );
    }

    abstract function getCallback();
}