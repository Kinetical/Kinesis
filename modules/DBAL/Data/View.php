<?php 
namespace DBAL\Data;

use \Core\Interfaces as I;

abstract class View extends \Core\Object implements I\Command
{
	protected $command;
	protected $adapter;
        private $_prepared = false;
	
	function __construct( Adapter $adapter = null )
	{
		if( $adapter instanceof Adapter )
                    $this->adapter = $adapter;

                parent::__construct();
	}
	
	function hasAdapter()
	{
		return ($this->adapter !== null) ? true : false;
	}
	
	function getAdapter()
	{
		return $this->adapter;
	}
	
	function setAdapter( Adapter $adapter )
	{
		$this->adapter = $adapter;
		
	}

        function prepared()
        {
            return $this->_prepared;
        }
	function prepare( Source $dataSource = null )
	{
            $this->_prepared = true;
	}
	
	function execute()
	{
            if( !$this->prepared() )
                $this->prepare();

            return $this->command->execute();
	}
	
	public function setCommand( $command )
	{
            $this->command = $command;
	}
	
	public function getCommand()
	{
            return $this->command;
	}
	
	function hasCommand()
	{
            return !is_null($this->command);
	}

        abstract function getDefaultQuery();
        abstract function getDefaultSelect();
        abstract function getDefaultInsert();
        abstract function getDefaultUpdate();
        abstract function getDefaultDelete();
}