<?php
namespace IO\File;

class Cache extends \Core\Cache
{
    private $_pathDelegate;

    function __construct( array $params = array() )
    {
        parent::__construct( $params );

        if( array_key_exists('DelegateTarget', $params ))
            $this->_pathDelegate = new \Core\Delegate( $params['DelegateTarget'], $params['DelegateMethod']);
    }

    protected function dirty( $name )
    {
        if( !($this->_pathDelegate instanceof \Core\Delegate) )
            return true;

        $cachePath = $this->getPath( $name );  // CACHE FILE LOCATION
        $sourcePath = $this->_pathDelegate( $name ); // SOURCE FILE LOCATION DELGATE

        if(    is_file( $cachePath )
            && is_file( $sourcePath )
            && filemtime( $cachePath ) > filemtime($sourcePath))
            return false;

        return true;
    }

    protected function has( $name )
    {
        if( $this->dirty( $name ))
            return false;

        $path = $this->getPath( $name );

        return is_file( $path );
    }

    protected function load( $name )
    {
        if( $this->parameters['Hash'] )
        $stream = $this->getStream( $name );
        $reader = new \IO\File\Reader( $stream );

        $stream->open();
        $contents = $reader->readToEOF();
        $stream->close();

        return $contents;
    }

    protected function save( $name, $value )
    {
        if( !is_string( $value ) )
            $value = (string)$value;

        $stream = $this->getStream( $name , \IO\Stream\Mode::Write );
        $writer = new \IO\File\Writer( $stream );

        $stream->open();

        $stream->write( $value );

        $stream->close();
    }
    
    protected function delete( $name )
    {
        //TODO: this
    }

    protected function getPath( $name )
    {
        $path = str_replace('/', '-', $name );

        if( $this->parameters['hashing'] === true )
            $path = md5( $path );

        return 'site'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$path.'.dat';
    }

    protected function getStream( $name, $mode = \IO\Stream\Mode::Read )
    {
        return new Stream( $this->getPath( $name ), $mode );
    }
}