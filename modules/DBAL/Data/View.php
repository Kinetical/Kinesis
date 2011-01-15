<?php 
namespace DBAL\Data;

use \Core\Interfaces as I;

class View extends \Core\Object implements I\Command
{
	private $_command;
	private $_dataAdapter;
        private $_prepared = false;
	
	function __construct( $adapter = null )
	{
		if( $adapter instanceof Adapter )
			$this->_dataAdapter = $adapter;

                parent::__construct();
	}
	
	function hasAdapter()
	{
		return ($this->_dataAdapter !== null) ? true : false; 
	}
	
	function getAdapter()
	{
		return $this->_dataAdapter;
	}
	
	function setAdapter( Adapter $adapter )
	{
		$this->_dataAdapter = $adapter;
		
	}

        function prepared()
        {
            return $this->_prepared;
        }
	function prepare()
	{
		//$this->Command->Query->Resource->View = $this;
                
		$this->_prepared = true;
	}
	
	function execute()
	{
		if( !$this->prepared() )
                    $this->prepare();
		
		return $this->_command->execute();
	}
	
	public function setCommand( $command )
	{
		return $this->_command = $command;
		
	}
	
	public function getCommand()
	{
		return $this->_command;
	}
	
	function hasCommand()
	{
		return ($this->_command !== null) ? true : false;
	}
}