<?php
namespace Core;

abstract class Loader extends Object
{
    protected $parameters;
    protected $manager;
    protected $cache;

    function __construct( array $params = null, Manager $manager = null )
    {
        $this->manager = $manager;

        parent::__construct();

        if( is_array( $params ))
            $this->setParameters( $params );

    }

    function initialize()
    {
        parent::initialize();

        $this->parameters = new \Core\Collection();

        if( !$this->caching() )
            if( $this->managed() )
                $this->setCache( $this->manager->getCache() );
            else
                $this->setCache( $this->getDefaultCache() );
    }

    protected function getDefaultCache()
    {
        $cacheClass = $this->parameters['CacheClass'];

        if( class_exists( $cacheClass ))
            return new $cacheClass( $this );
        
        return new \Core\Loader\Cache( $this );
    }

    function getParameters()
    {
        return $this->parameters;
    }

    function setParameters( array $params )
    {
        $this->parameters->merge( $params );
    }

    protected function caching()
    {
        return ( $this->cache instanceof Cache
                 && $this->cache->enabled() );
    }

    function getCache()
    { 
        return $this->cache;
    }

    function setCache( Cache $cache )
    {
        $this->cache = $cache;
    }

    function managed()
    {
        return ($this->manager instanceof Manager);
    }

    function getManager()
    {
        return $this->manager;
    }

    function setManager( Manager $manager )
    {
        $this->manager = $manager;
    }

    abstract function parse( $path );
    abstract function match( $path );
    abstract function execute( array $params = null );

    function __invoke( array $params = null )
    {
        return $this->execute( $params );
    }
}
