<?php
namespace IO\Output;

class Writer extends \IO\Stream\Writer
{
    function write( $data )
    {
        $output = $this->stream->getOutput();

        $output.= $data;

        $this->stream->setOutput( $output );

        return $data;
    }
}