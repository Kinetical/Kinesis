<?php
namespace IO\Stream;

abstract class Wrapper extends \Core\Object
{
    protected $stream;
    protected $buffer;


    function __construct( \IO\Stream $stream = null )
    {
        if( !is_null( $stream ))
            $this->setStream( $stream );
        
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
        return $this->stream;
    }

    function setStream( \IO\Stream $stream )
    {
        $this->stream = $stream;
    }

    function getEncoding()
    {
        return $this->stream->getEncoding();
    }

    function setEncoding( $encoding )
    {
        $this->stream->setEncoding( $encoding );
    }

    function open()
    {
        return $this->stream->open();
    }

    function close()
    {
        $this->stream->close();
    }

    abstract function getCallback();
}