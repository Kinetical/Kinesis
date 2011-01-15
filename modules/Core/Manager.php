<?php
namespace Core;

abstract class Manager extends Object
{
    private $_loaders;
    private $_cache;

    function initialize()
    {
        parent::initialize();

        if( !$this->caching() )
            $this->setCache( new \Core\Cache\ManagerCache ( $this ));
        if( !$this->Type->hasEvents( array('parse', 'match', 'load')))
        {
            $this->Type->addEvent( new \Core\Event('parse') );
            $this->Type->addEvent( new \Core\Event('match') );
            $this->Type->addEvent( new \Core\Event('load') );
        }
    }

    function caching()
    {
        return !is_null( $this->_cache );
    }

    function getCache()
    {
        return $this->_cache;
    }

    function setCache( \Core\Cache\ManagerCache $cache )
    {
        $this->_cache = $cache;
    }

    function getLoaders()
    {
        return $this->_loaders;
    }

    function setLoaders( array $loaders )
    {
        foreach( $loaders as $loader )
            $this->addLoader( $loader );
    }

    function getLoader( $loaderName )
    {
        return $this->_loaders[$loaderName];
    }

    function addLoader( Loader $loader )
    {
        if( !$loader->managed() )
            $loader->setManager( $this );

        $this->_loaders[ $loader->getName() ] = $loader;
    }

    function removeLoader( $loaderName )
    {
        unset($this->_loaders[ $loaderName ]);
    }

    protected function parse()
    {
        $args = func_get_arts();

        foreach( $this->_loaders as $name => $loader )
            if( ($match =
                    call_user_func_array( array( $loader, 'parse' ),
                                          $args ))
                    !== false )
                break;

        return $match;
    }

    protected function match()
    {
        $args = func_get_args();

        foreach( $this->_loaders as $name => $loader )
        {
            if( ($match =
                call_user_func_array( array( $loader, 'match' ),
                                      $args )) == false )
                continue;

            $this->add( $match, $loader );

            return $match;
        }

        return false;
    }

    protected function load( $args )
    {
        if( $this->has( $args ))
            return $this->get($args);
        elseif( ($match = $this->match( $args )) !== false )
        {
            return $this->get($match)->load( $match, $args );
        }

        throw new \Core\Exception('Path not found: '.$path);
    }
        
}