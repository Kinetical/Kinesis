<?php
namespace IO\Archive\GZ;

class File extends \IO\File
{
    function getSize( $uncompressed = false )
    {
        if( $uncompressed )
        {
            $stream = new \IO\File\Stream( $this, 'rb' );
            $stream->open();
            $stream->seek( -1, SEEK_END );

            $reader = new \IO\File\Reader( $stream );
            $buffer = $reader->read( 4 );
            $stream->close();

            return end(unpack("V", $buffer));
        }

        return parent::getSize();
    }
}