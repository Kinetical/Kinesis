<?php
namespace Core\Object;

class Loader extends \IO\Serial\Loader
{

    function initialize()
    {
        if( !$this->caching() )
            parent::setCache(
                    new \Core\Object\Cache($this));

        parent::initialize();
    }
    function match( $path )
    {
        $cache = $this->getCache();

        if( array_key_exists( $path, $cache ) )
            return $path;

        return false;
    }

    

    /*
     * @param string $path pass either an OID(serialized) or class name(factory)
     */
    function load( $path, $args = null )
    {
        $cache = $this->getCache();
        
        if( is_string( $path ) )
            if( array_key_exists( $path, $cache ) )
                return $cache[ $path ];
            elseif( class_exists( $path ))
            {
                $type = new \Core\Object\ReflectionClass( $path );

                $object = $type->newInstanceArgs( $args );
                $object->Type = $type;

                $cache[] = $object;

                return $object;
            }


        throw new \Core\Exception('Object('.$path.') not found');
    }
}
