<?php
namespace IO\Output;

class Stream extends \IO\Stream
{
    private $_output;

    function __construct( $output = '', $mode = \IO\Stream::READ )
    {
        $this->setOutput( $output );

        parent::__construct( $mode );
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

    function __toString()
    {
        return $this->getOutput();
    }
}
