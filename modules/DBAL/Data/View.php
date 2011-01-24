<?php 
namespace DBAL\Data;

use \Util\Interfaces as I;

abstract class View extends \Core\Object implements I\Nameable
{
    private $_prepared = false;

    protected $parameters;
    protected $command;
    protected $adapter;

    function __construct( array $params = array(), Adapter $adapter = null )
    {
        if( $adapter instanceof Adapter )
            $this->adapter = $adapter;

        parent::__construct();

        $this->setParameters( $params );
    }

    function initialize()
    {
        parent::initialize();
        
        $this->parameters = new \Util\Collection();
    }

    function getName()
    {
        return $this->parameters['name'];
    }

    function setName( $name )
    {
        $this->parameters['name'] = $name;
    }

    function getParameters()
    {
        return $this->parameters;
    }

    function setParameters( array $params )
    {
        $this->parameters->merge( $params );
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
    function prepare( Source $dataSource = null )
    {
        $this->_prepared = true;

        return $this->command;
    }

    protected function execute()
    {
        if( !$this->prepared() )
            $command = $this->prepare();
        else
            $command = $this->command;

        return $command();
    }

    function __invoke()
    {
        return $this->execute();
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