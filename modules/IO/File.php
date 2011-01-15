<?php
namespace IO;

class File extends File\Info
{
	function __construct( $path, IO\Directory $parent = null )
	{
            parent::__construct( $path, $parent );
	}

	function getSize()
	{
            if( $this->exists() )
                return filesize( $this->getPath() );

            return false;
	}
	
	function exists()
	{
            return file_exists( $this->getPath() );
	}
	
	function readable()
	{
            if( $this->exists() )
                return is_readable( $this->getPath() );

            return false;
	}
	
	function writable()
	{
            if( $this->exists() )
                return is_writable( $this->getPath()  );
            return false;
	}
	
	function delete()
	{
            if( $this->exists() )
                unlink( $this->getPath()  );
	}
	
	function copy( $path )
	{
            if( $this->exists() )
            {
                copy( $this->getPath(), $path );
                return new File( $path, $this->getParent() );
            }
	}
	
	function move( $path )
	{
            if( $this->exists() )
            {
                $newfile = $this->copy( $path );
                $this->delete();
                return $newfile;
            }
	}

        function isLink()
        {
            return is_link( $this->getPath() );
        }
	
	function getExtension()
	{
		$info = $this->getPathInfo();
		return $info['extension'];
	}
	
	function getDirectoryName()
	{
		$info = $this->getPathInfo();
		return $info['dirname'];
	}

        function getParent()
        {
            if( !$this->hasParent() )
                $this->setParent( new \IO\Directory( $this->getDirectoryName() ) );
            
            return parent::getParent();
        }
	
	function getBaseName()
	{
		$info = $this->getPathInfo();
		return $info['basename'];
	}
	
	function getFileName()
	{
		$info = $this->getPathInfo();
		return $info['filename'];
	}
	
	function getRealPath()
	{
		return realpath( $this->getPath()  );
	}
	
	function touch( $time = null, $atime = null )
	{
		return touch( $this->getPath() , $time, $atime );
	}
	
	function getMode()
	{
		return fileperms( $this->getPath()  );
	}
	
	function setMode( int $mode )
	{
		return chmod( $this->getPath() , $mode );
	}
	
	function getOwner()
	{
		return fileowner( $this->getPath()  );
	}
	
	function setOwner( $user )
	{
		return chown( $this->getPath() , $user );
	}
	
	function getTime()
	{
		return filemtime( $this->getPath()  );
	}
	
	function getGroup()
	{
		return filegroup( $this->getPath()  );
	}
	
	function setGroup( $group )
	{
		return chgrp( $this->getPath() , $group );
	}
	
	function getAccessTime()
	{
		return fileatime( $this->getPath()  );
	}
	
	function upload( $input )
	{
		//TODO: CHECK PRIVS
		global $_FILES;
		$filename = $_FILES[$input]['name'];
		//print_r( $_FILES );
		$path_parts = pathinfo($filename);
		$name = substr($path_parts['basename'],0,strpos($path_parts['basename'],'.'));
		$extension = $path_parts['extension'];
		$this->Path .= $name.'.'.$extension;
		//echo $this->Path;
		if (move_uploaded_file($_FILES[$input]['tmp_name'], $this->getPath() ) ) {
		   return true;;
		} else {
		   return false;
		}
		
		return false;
	}
}