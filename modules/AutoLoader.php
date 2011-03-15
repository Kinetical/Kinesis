<?php

class AutoLoader
{
    protected static $_instance;

    public static function getInstance() {
            if(   !self::$_instance instanceof self )
                   self::$_instance = new self;
            return self::$_instance; }

    private $_coreInitialized = false;
    private $_delayLoaded = array();
    private $_core;
    private $_extensions;
    private $_path;

    function __construct( $path = null, $exts = null )
    {
        if( is_null( $path ))
            $path = __DIR__;
        if( is_string( $path ))
            $this->setPath( $path );
        if( is_null( $exts ) )
            $exts = 'php';
        if( is_string( $exts ))
            $this->addExtension($exts );
        elseif( is_array( $exts ))
            $this->setExtensions( $exts );
    }

    function getPath()
    {
        return $this->_path;
    }

    function setPath( $path )
    {
        $this->_path = $path;
    }

    function addExtension( $ext )
    {
        $this->_extensions[ $ext ] = '.'.$ext;
    }

    function removeExtension( $ext )
    {
        unset( $this->_extensions[ $ext ]);
    }

    function getExtensions()
    {
        return $this->_extensions;
    }

    function setExtensions( array $extensions )
    {
        foreach( $extension as $ext )
            $this->addExtension( $ext );

    }

    function initialize( Core $core )
    {
        $this->setCore( $core );
        $this->_coreInitialized = true;
        
        if( is_array( $this->_delayLoaded ))
            foreach( $this->_delayLoaded as $module )
                $core->loadModule( $module );
    }

    function delayLoad( $moduleName )
    {
        $this->_delayLoaded[$moduleName] = $moduleName;
    }

    function isInitialized()
    {
        return $this->_coreInitialized;
    }

    function getCore()
    {
        return $this->_core;
    }

    function setCore( Core $core )
    {
        return $this->_core = $core;
    }
    function register()
    {
        set_include_path( $this->_path );
        spl_autoload_extensions(implode(',',$this->_extensions) );

        spl_autoload_register();
        //spl_autoload_register( array( $this, 'myLoad' ) );
    }

    function myLoad( $class )
    {
        $class = str_replace('\\','/',$class);
        $path = 'modules/'.$class.'.php';
        include($path );
    }

    /*protected function classPath ($filename)
    {       
        return $this->_workingPath.$filename;
    }

    function load( $className )
    {
        $fullPath = $this->parsePath( $className );

        if( is_file($classPath = $this->classPath( $fullPath )))
            require_once( $classPath );
    }

    protected function loadModule( $moduleName )
    {
        if( $this->_coreInitialized
            && !$this->_core->hasModule( $moduleName ))
                $this->_core->loadModule( $moduleName );
        elseif( !$this->_coreInitialized )
            $this->delayLoad( $moduleName );
    }

    protected function parsePath( $classPath )
    {
        $pos = strpos( $classPath, '\\');
        $pos2= strrpos( $classPath, '\\');

        $moduleName = substr( $classPath, 0, $pos );
        $className = substr( $classPath, $pos2+1 );

        $this->loadModule( $moduleName );

        return 'modules'.DIRECTORY_SEPARATOR.$classPath.'.php';
    }*/
}