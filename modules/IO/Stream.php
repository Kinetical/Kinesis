<?php
namespace IO;

abstract class Stream extends \Core\Object
{
    const WRITE = 'w';
    const READ = 'r';

    private $_mode;
    private $_encoding;

    function __construct( $mode = Stream::READ )
    {
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
        return (strpos($this->_mode,self::READ) !== false )
                ? true
                : false;
    }

    function isWrite()
    {
        return (strpos($this->_mode,self::WRITE) !== false )
                ? true
                : false;
    }

    abstract function getDefaultEncoding();

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
}