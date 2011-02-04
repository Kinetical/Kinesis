<?php 

class Core
{
    protected static $_instance;
    private static $_initialized;

    public static function getInstance() {
            if( !self::$_instance instanceof self )
                    self::$_instance = new self;
            return self::$_instance; }

    private $_modules;
    private $_context;
    private $_dataBase;
    
    private $_configuration;
    private $_loader;

    function __construct()
    {
        $this->initialize();
    }

    function initialize()
    {
        $this->_configuration = new Core\Configuration();
        //$this->_loader = new \Core\Loader\ObjectLoader('site\cache');

        $this->loadModule( 'Core' );

        $this->setInitialized( true );
        
    }

    private function setInitialized( $init )
    {
        self::$_initialized = $init;
        AutoLoader::getInstance()->initialize( $this );
    }

    function getInitialized()
    {
        return self::initialized();
    }

    static function initialized()
    {
        return self::$_initialized;
    }

    function getConfiguration()
    {
        return $this->_configuration;
    }

    function setConfiguration( \Core\Configuration $config )
    {
        $this->_configuration = $config;
    }

    function getLoader()
    {
        return $this->_loader;
    }

    function getDatabase()
    {
        return $this->_dataBase;
    }

    function setDatabase( \DBAL\Database $database )
    {
        $this->_dataBase = $database;
    }

    function getModuleDirectory()
    {
        return new IO\Directory('modules');
    }

    function addModule( Core\Module $module )
    {
        $this->_modules[$module->getName()] = $module;
    }
    function removeModule( $moduleName )
    {
        unset( $this->_modules[ $moduleName ]);
    }
    function hasModule( $moduleName )
    {
        return array_key_exists( $moduleName, $this->_modules );
    }
    function getModules()
    {
        return $this->_modules;
    }
    function setModules( array $modules )
    {
        foreach( $modules as $module )
            $this->addModule( $module );
    }

    function getContext()
    {
        return $this->_context;
    }

    function setContext( MVC\Context $context )
    {
        $this->_context = $context;
    }

    function loadModule( $moduleName )
    {
       if( !$this->hasModule( $moduleName ))
           $this->addModule( new Core\Module( $moduleName ) );
    }

    function hasComponent( $componentName, $type = \Module::Component )
    {
        return ( $module = $this->getComponentModule( $componentName, $type ))
               ? true
               : false;
    }

    function hasController( $controllerName )
    {
        return $this->hasComponent( $controllerName, \Module::Controller );
    }

    function getComponentModule( $componentName, $type = Core\Module::Component )
    {
        //TODO: cache in memory
        foreach( $this->_modules as $module )
            if( $module->hasComponent( $componentName, $type ))
                return $module;

        throw new Exception( $componentName . ' not found.');
    }
}