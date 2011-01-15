<?php
namespace IO;

class FileResource extends \Core\Resource
{

	function initialize()
	{
            parent::initialize();
            
		//if( !$this->Stream->isOpen() )
		//	$this->Stream->open();
	}

	function flush()
	{
		return implode( parent::flush() );
	}

	function next()
	{
		parent::next();

                $mode = $this->getStream()->getMode();
		if( $mode->isRead() )
			$this->clear();

		$this->setBuffer ($mode->execute( $this->getBuffer() ));
		$this->increment();
	}

	function valid()
	{
		return !( $this->getStream()->isEOF() );
	}

	function Lock()
	{
		$this->getStream()->Lock();
	}

	function Unlock()
	{
		$this->getStream()->Unlock();
	}
}