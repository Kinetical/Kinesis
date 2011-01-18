<?php
namespace IO\Resource;

abstract class Stream extends \IO\Stream
{
    private $_pointer;
    private $_resource;
    private $_bufferSize = 1024;
    private $_timeout;

    function __construct( $resource = null, $mode = \IO\Stream::READ )
    {
        if( !is_null( $resource ))
            $this->setResource( $resource );

        parent::__construct( $mode );
    }

    protected function getResource()
    {
        return $this->_resource;
    }

    protected function setResource( $resource )
    {
        $this->_resource = $resource;
    }

    function getPointer()
    {
        return $this->_pointer;
    }

    function setPointer( $pointer )
    {
        if( is_resource( $pointer ))
            $this->_pointer = $pointer;
    }

    function getBufferSize()
    {
        return $this->_bufferSize;
    }

    function setBufferSize( $bufferSize )
    {
        $this->_bufferSize = $bufferSize;
    }
    
    function getTimeout()
    {
        if( is_null( $this->_timeout ))
            $this->_timeout = $this->getDefaultTimeout();
        
        return $this->_timeout;
    }

    abstract function getDefaultTimeout();
    

    protected function setTimeout( $timeout )
    {
        $this->_timeout = $timeout;
    }

    

    abstract function open();
    abstract function close();

    function isOpen()
    {
        return is_resource( $this->_pointer );
    }

    function __destruct()
    {
        $this->close();
        parent::__destruct();
    }
}
