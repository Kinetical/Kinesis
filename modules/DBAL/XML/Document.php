<?php
namespace DBAL\XML;

class Document extends \Kinesis\Object
{
    private $_root;
    private $_version;
    private $_encoding;

    function __construct( $version, $encoding = 'UTF-8' )
    {
        parent::__construct();

        $this->_version = $version;
        $this->_encoding = $encoding;
    }

    function getRoot()
    {
        return $this->_root;
    }

    function setRoot( $root )
    {
        $this->_root = $root;
    }

    function getVersion()
    {
        return $this->_version;
    }

    function setVersion( $version )
    {
        $this->_version = $version;
    }

    function getEncoding()
    {
        return $this->_encoding;
    }

    function setEncoding( $encoding )
    {
        $this->_encoding = $encoding;
    }
}