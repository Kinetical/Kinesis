<?php
namespace IO;

abstract class Stream extends \Core\Object
{
    const WRITE = 'w';
    const READ = 'r';

    private $_pointer;
    private $_resource;
    private $_bufferSize = 1024;
    private $_mode;
    private $_timeout;
    private $_encoding;

    function __construct( $resource = null, $mode = Stream::READ )
    {
        if( !is_null( $resource ))
            $this->setResource( $resource );

        $this->_mode = $mode;

        parent::__construct();
    }

    function getMode()
    {
        return $this->_mode;
    }

    function setMode( $mode )
    {
        $this->_mode = $mode;
    }

    function isRead()
    {
        return (strpos($this->_mode,self::READ) > 0 )
                ? true
                : false;
    }

    function isWrite()
    {
        return (strpos($this->_mode,self::WRITE) > 0 )
                ? true
                : false;
    }

    protected function getResource()
    {
        return $this->_resource;
    }

    protected function setResource( \Core\Object $resource )
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
    abstract function getDefaultEncoding();

    protected function setTimeout( $timeout )
    {
        $this->_timeout = $timeout;
    }

    function getEncoding()
    {
        if( is_null( $this->_encoding ))
            $this->_encoding = $this->getDefaultEncoding ();
        
        return $this->_encoding;
    }


    protected function setEncoding( $charSet )
    {
        $this->_encoding = $charSet;
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
