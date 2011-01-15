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
    private $_directories = array();
    private $_loader;

    function __construct()
    {
        $this->initialize();
    }

    function initialize()
    {
        $this->_configuration = new Core\Configuration();
        $this->_context = new MVC\Context();
        $this->_loader = new \Core\Loader\ObjectLoader('site\cache');

        $this->loadModule( 'Core' );

        $this->loadDirectories();

        $this->setInitialized( true );
        
    }

    private function loadDirectories()
    {
        $it = $this->getDirectoryIterator();
        foreach( $it as $name => $dir )
            $this->addDirectory ( $dir );
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

    function addDirectory( $dir )
    {
        if( is_string( $dir ))
        {
            $path = $dir;
            $baseName = basename( $path );
        }
        elseif( $dir instanceof \SplFileInfo )
        {
            $path = $dir->getPath();
            $baseName = $dir->getBasename();           
        }

        if( substr( $path, 0, 2) == ".\\")
                $path = substr( $path, 2 );


        if( $baseName == '.svn'
            || $baseName == 'tmp'
            || $baseName == 'text-base'
            || $baseName == 'prop-base'
            || $baseName == 'props'
            || $baseName == 'entries'
            || $baseName == 'all-wcprops')
            return;

        if( $path == 'modules' )
            $this->loadModule ( $baseName );

        $fullPath = $path . DIRECTORY_SEPARATOR. $baseName;

        return $this->_directories[$fullPath]=$fullPath;
    }
    function removeDirectory( $path )
    {
        unset( $this->_directories[$path] );
    }
    function getDirectories()
    {
        return $this->_directories;
    }

    function setDirectories( $directories )
    {
        $this->_directories = $directories;
    }

    protected function getDirectoryIterator( $recursive = true )
    {
        if( $recursive == true )
            return new RecursiveIteratorIterator(
                new ParentIterator(new RecursiveDirectoryIterator('.')),
                        RecursiveIteratorIterator::SELF_FIRST);
            else
                return new \RecursiveDirectoryIterator('.');
    }

    private function connect()
    {
         $databaseConfig = $this->_configuration['database'];
         $userConfig = $this->_configuration['database/user'];

         try
         {
             $dataBase = new \DBAL\Database\SQLDatabase( $databaseConfig['name'] );
             $connection =  new \DBAL\Connection\SQLConnection
                                     ( $databaseConfig['host'],
                                       new \DBAL\User\SQLUser
                                                ( $userConfig['name'],
                                                  $userConfig['password']));
             $connection->Connect();
             $dataBase->setConnection( $connection );
             $dataBase->Select();

             $this->setDatabase( $dataBase );
         }
         catch( \Exception $e )
         {
             throw new \Core\Exception('Unable to connect to database('.$databaseConfig['name'].')');
         }
    }

    function getDatabase()
    {
        if( !($this->_dataBase instanceof \DBAL\Database ))
            $this->connect();

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