<?php
namespace IO\Resource;

use IO\Stream\Mode;

abstract class Stream extends \IO\Buffer\Stream
{
    protected $pointer;
    protected $resource;
    protected $mode;

    function __construct( $resource = null, $mode = Mode::Read )
    {
        if( !is_null( $resource ))
            $this->setResource( $resource );

        $this->setMode( $mode );

        parent::__construct();
    }

    protected function getResource()
    {
        return $this->resource;
    }

    protected function setResource( $resource )
    {
        $this->resource = $resource;
    }

    function getPointer()
    {
        return $this->pointer;
    }

    function setPointer( $pointer )
    {
        if( is_resource( $pointer ))
            $this->pointer = $pointer;
    }

    function getMode()
    {
        return $this->mode;
    }

    function setMode( $mode )
    {
        if( is_string( $mode ))
            $mode = new Mode( $mode );

        $this->mode = $mode;
    }

    function isRead()
    {
        return $this->mode->is( Mode::Read );
    }

    function isWrite()
    {
        return $this->mode->is( Mode::Write );
    }

    abstract function open();
    abstract function close();

    function isOpen()
    {
        return is_resource( $this->pointer );
    }

    function __destruct()
    {
        $this->close();
        parent::__destruct();
    }
}
