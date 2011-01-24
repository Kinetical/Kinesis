<?php
namespace IO\Output;

class Stream extends \IO\Stream
{
    private $_output;

    function __construct( $output = '' )
    {
        $this->setOutput( $output );

        parent::__construct();
    }

    function getOutput()
    {
        return $this->_output;
    }

    function setOutput( $output )
    {
        $this->_output = $output;
    }

    function getDefaultEncoding()
    {
        return 'UTF-8';
    }

    function getDefaultTimeout()
    {
        return -1;
    }

    function __toString()
    {
        return $this->getOutput();
    }
}
