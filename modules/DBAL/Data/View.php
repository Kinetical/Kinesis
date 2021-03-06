<?php 
namespace DBAL\Data;

use \Util\Interfaces as I;

abstract class View implements I\Nameable
{
    private $_prepared = false;

    public $Parameters;
    protected $command = false;
    protected $adapter;

    function __construct( array $params = array(), Adapter $adapter = null )
    {
        if( $adapter instanceof Adapter )
            $this->adapter = $adapter;

        $this->Parameters = new \Util\Collection( $params );
    }

    function getName()
    {
        return $this->Parameters['name'];
    }

    function setName( $name )
    {
        $this->Parameters['name'] = $name;
    }

    function getParameters()
    {
        return $this->Parameters;
    }

    function setParameters( array $params )
    {
        $this->Parameters->merge( $params );
    }

    function hasAdapter()
    {
        return !is_null($this->adapter);
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
    function prepare( $source = null )
    {
        $this->_prepared = true;

        return $this->command;
    }

    function clear()
    {
        $this->_prepared = false;
        $this->command = false;
        $this->adapter->clear();
    }

    protected function execute( &$dataSource = null )
    {
        if( !$this->prepared() )
            $command = $this->prepare( $dataSource );
        else
            $command = $this->command;

        if( $dataSource instanceof \Util\Collection\Persistent &&
            $this->adapter->isRead() )
            $dataSource->snapshot();

        if( $dataSource instanceof \DBAL\Data\Source &&
            $dataSource->hasMap() )
            $command->setHandler( $dataSource->getHandler() );

        $result = $command();

        if( !is_null( $dataSource ) &&
            $this->adapter->isRead() )
            if( $dataSource instanceof \Util\Collection )
                $dataSource->Data = $result;
            else
                $dataSource = $result;

        $this->clear();
        
        if( $dataSource instanceof \DBAL\Data\Source )
            $dataSource->Map->clear();

        return $result;
    }

    function __invoke( &$dataSource = null )
    {
        return $this->execute( $dataSource );
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
        return $this->command !== false;
    }

    abstract function getDefaultQuery();
    function getDefaultSelect()
    {
        return $this->getDefaultQuery();
    }
    function getDefaultInsert()
    {
        return $this->getDefaultQuery();
    }
    function getDefaultUpdate()
    {
        return $this->getDefaultQuery();
    }
    function getDefaultDelete()
    {
        return $this->getDefaultQuery();
    }
}