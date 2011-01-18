<?php
namespace DBAL\Data;

class Item extends \Core\Collection
{

	private $_dirty;
	
	function __construct( array $data = null )
	{
		parent::__construct( $data );
		$this->clean();
	}
	function isDirty( $name = null )
	{
		if( $name !== null )
			return array_key_exists( $name, $this->_dirty );
		return count($this->_dirty) > 0 ? true : false;
	}
	
	function clear()
	{
		$this->_dirty= $this->Data;
		$this->Data = array();
	}
	
	function getChanges()
	{
		return array_intersect_key( $this->Data, $this->_dirty );
	}
	
	function getClean( $name )
	{
		if( !$this->isDirty( $name ) )
			return $this->$name;
		
		return $this->_dirty[ $name ];
	}
	
	function getDirty()
	{
		if( $this->isDirty() )
			return $this->_dirty;
			
		return false;
	}
	
	protected function clean()
	{
		$this->_dirty = array();
	}
	
	protected function rollback()
	{
            if( array_walk( $this->_dirty, array( $this, '__set') ))
            {
                    $this->clean();
                    return true;
            }

            return false;
	}
	
	public function __set( $name, $value )
	{
		$this->dirty( $name );
		parent::__set( $name, $value );
	}
	
	protected function dirty( $name )
	{
		$this->_dirty[$name] = $this[$name];
	}
}