<?php 
namespace IO;

use \Core\Interfaces as I;

class Directory extends File\Info
{
    private $_resource;

    function __construct( $path, \IO\Directory $parent = null )
    {
        $this->setPath( $path );

        if( !String::endsWith( $this->Path, '/'))
                $this->Path .= '/';

        parent::__construct( $path, $parent );
    }

    function getResource()
    {
        if( $this->exists() )
            $this->_resource = $this->open();

        return $this->_resource;
    }

    function exists()
    {
        return is_dir( $this->getPath() ) ;
    }

    function create()
    {
        return mkdir( $this->getPath() );
    }

    function open()
    {
        return opendir( $this->getPath()  );
    }

    function close()
    {
        closedir( $this->_resource );
    }

    function scan( $sorting_order = 0 )
    {
        $scan = scandir( $this->getPath(), $sorting_order );
        unset( $scan[0]);
        unset( $scan[1]);
        return $scan;
    }

    function isOpen()
    {
        return is_resource( $this->_resource );
    }

    function read()
    {
        $path = readdir( $this->_resource );
        if( is_file( $path ))
            return new \IO\File( $path );
        elseif( is_dir( $path ))
            return new \IO\Directory( $path, false, $this );
    }

    function __destruct()
    {
        if( isset( $this->_resource ) )
            closedir( $this->_resource );
    }

    protected function deleteEmpty()
    {
        if( $this->exists() )
            return rmdir( $this->getPath() );

        return false;
    }

    function delete()
    {
            $path = $this->getPath();

            if( $this->exists() )
            {
                if( !$this->isOpen() )
                    $this->open();


            }
            while($info = $this->read() )
                if( $info instanceof File )
                    unlink( $info->getPath() );
                elseif( $info instanceof Directory )
                    $info->delete();

            $this->close();
            return $this->deleteEmpty();
    }

    function getBaseName()
    {
        return basename( $this->getPath() );
    }

    function __toString()
    {
        return $this->getPath();
    }
}

class String
{
	static function startsWith($string, $char)
	{
	    $length = strlen($char);
	    return (substr($string, 0, $length) === $char);
	}
	
	static function endsWith($string, $char)
	{
	    $length = strlen($char);
	    $start =  $length *-1; //negative
	    return (substr($string, $start, $length) === $char);
	}
	
}