<?php 
namespace Core;

use \Util\Interfaces as I;

class Object implements I\Object
{
    private $_initialized = false;

    public $Oid;
    public $Data = array();
    public $Type;

    private static $types;

    function  __destruct() {
//        \Core::getInstance()->getLoader()->remove( $this->Oid );

    }

    private static function setCachedType( Object\ReflectionClass $objReflection )
    {
        return self::$types[ $objReflection->getName() ] = $objReflection;
    }

    private static function hasType( $typeName )
    {
        if( is_string( $typeName )
            && array_key_exists( $typeName, self::$types ) )
                return self::$types[$typeName];

        return false;
    }

    public function __construct()
    {
        if( !$this->initialized() )
             $this->initialize();
    }

    function initialized()
    {
        return $this->_initialized;
    }

    function initialize()
    {
        if( $this->Type instanceof Object\ReflectionClass )
        {
            if(!self::hasType($this->Type->name))
               self::setCachedType ( $this->Type );
        }
        elseif( ($this->Type = self::hasType( $typeName = get_class( $this ) ) ) == false )
        {
            $this->Type = self::setCachedType(
                          new Object\ReflectionClass( $typeName ));
        }

        $this->_initialized = true;
//        if( $this->Oid == null
//            && \Core::initialized() )
//            $loader = \Core::getInstance()->getLoader()->add( $this );
    }


    static function getObject($id)
    {
        //        return \Core::getInstance()->getLoader()->has( $id );

        //return array_key_exists( $id, self::$instances ) ? self::$instances[$id] : null;
    }

    function getType() {

       return $this->Type;
    }

    function getOid()
    {
        return $this->Oid;
    }

    function equals( Object $object )
    {
        return ($this->Oid == $object->Oid) ? true : false;
    }

    function setData( $data )
    {
        if( is_array( $data )
            || $data instanceof Traversable )
            $this->Data = $data;
        else
            $this->Data[] = $data;

        return $data;
    }

    function getData()
    {
        return $this->Data;
    }

    function __toString()
    {
        return '';
    }

    function __get( $name )
    {
        if( $this->Type instanceof \Core\Object\ReflectionClass
            && $this->Type->hasProperty( $this, $name )
            && ($result = $this->Type->getPropertyValue( $this, $name )) !== null )
            return $result;
        if( array_key_exists( $name, $this->Data ))
            $result = $this->Data[$name];

        return $result;
    }



    function __set( $propertyName, $value )
    {
        if( $this->Type instanceof Object\ReflectionClass
            && $this->Type->hasProperty( $this, $propertyName ))
            return $this->Type->setPropertyValue( $this, $propertyName, $value );


        if( $propertyName == null )
            if( $value instanceof I\Nameable )
                $propertyName = $value->getName();
            else
                $propertyName = count( $this->Data );

        $this->Data[ $propertyName ] = $value;
    }

    function __unset( $name )
    {
        $this->Type->setPropertyValue( $this, $name, null );
    }

    function __isset( $name )
    {
        if( array_key_exists( $name, $this->Data ))
            return true;
        if( $this->Type->hasPropertyData( $this, $name ))
            return true;   
        if( $this->Type->hasProperty( $this, $name ) )
            return true;

        return false;
    }

    function __call( $method, $arguments )
    {
        if( ($results = $this->Type->invoke( $this, $method, $arguments ) ) !== null )
            if( $results instanceof \Core\Event
                && method_exists( $this, $method ))
                return call_user_func_array(array( $this, $method ), $arguments );
            else
                return $results;

        if( $this->Type->hasMethod( $methodName )
            && $this->Type->getMethod( $methodName )->isProtected() )
        {
            if( $this->Type->hasPreProcess($methodName)
                && $this->Type->preProcess($this, $methodName, $arguments) == false)
                return null;

                $results = call_user_func_array( array( $this, $methodName ), $arguments );
            if( $this->Type->hasPostProcess($methodName))
                $this->Type->postProcess ($this, $methodName, $arguments);
        }

        if( $results !== null )
            return $results;

        return null;
    }

    
}