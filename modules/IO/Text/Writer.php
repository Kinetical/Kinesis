<?php
namespace IO\Text;

class Writer extends \IO\Stream\Writer
{
    private $_lineDelimiter = "\r\n";

    function getLineDelimiter()
    {
        return $this->_lineDelimiter;
    }

    function setLineDelimeter( $delimiter )
    {
        $this->_lineDelimiter = $delimiter;
    }

    function writeLine( $data = null )
    {
        $this->write( (string)$data . $this->_lineDelimiter );
    }
}
