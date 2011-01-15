<?php
namespace IO\File;

class Stream extends \IO\Stream
{
    function __construct( $file = null, $mode = \IO\Stream::READ )
    {
        if( is_string( $file ) )
            $file = new \IO\File( $file );

        if( $file instanceof \IO\File
            && !$file->exists() )
            throw new \IO\Exception('File not found: '.$file->getPath() );

        parent::__construct( $file, $mode );
    }

    function getFile()
    {
        return parent::getResource();
    }

    function setFile( $file )
    {
        parent::setResource( $file );
    }

    function getDefaultTimeout()
    {
        return 30;
    }

    function getDefaultEncoding()
    {
        return 'UTF-8';
    }

    function open()
    {
        $file = $this->getFile();

        $pointer = fopen( $file->getPath(), $this->getMode() );
        
        $this->setPointer( $pointer );
        if( !is_null( $timeout = $this->getTimeout() ) )
            stream_set_timeout( $pointer,$timeout );

//        if( !is_null( $encoding = $this->getEncoding() ) )
//            stream_encoding( $resource, $encoding );

        return $pointer;
    }

    function close()
    {
        if( !$this->isOpen() )
            return true;

        fclose( $this->getPointer() );

        return true;
    }

    function eof()
    {
        if( !$this->isOpen() )
            return true;

        return feof( $this->getPointer() );
    }

    function seek( $offset = 0 )
    {
        fseek( $this->getPointer(), $offset );
    }

    function rewind()
    {
        return rewind( $this->getPointer() );
    }


    function lock( $operation = LOCK_EX, &$wouldblock = null )
    {
        return flock( $this->getPointer(), $operation, $wouldblock );
    }

    function unlock()
    {
        $this->lock( LOCK_UN );
    }
}