<?php
namespace Core;

final class Delegate extends Object
{
    private $method;
    private $target;

    function __construct( $target, $method = null )
    {
        $this->setTarget( $target );
        $this->setMethod( $method );
        parent::__construct();
    }

    function getTarget()
    {
        return $this->target;
    }

    function setTarget( $target )
    {
        if( is_string( $target ) )
            if( !class_exists( $target ))
                throw new \Core\Exception('Delegate target class('.$target.') not found');
        elseif( !is_object( $target ))
            throw new \Core\Exception('Delegate target must be class or object.');

        $this->target = $target;
    }

    function getMethod()
    {
        return $this->method;
    }

    function setMethod( $method )
    {
        $this->method = $method;
    }

    function callable()
    {
        return is_callable( $this->toCallback() );
    }

    function toCallback()
    {
        if( is_string( $this->method ))
            if( $this->isStatic() )
                return $this->target.$this->getOperator().$this->method;
            else
                return array( $this->target, $this->method );

        return $this->target;
    }

    function isStatic()
    {
        return is_string( $this->target );
    }

    function isObject()
    {
        return is_object( $this->target );
    }

    function getOperator()
    {
        if( $this->isStatic() )
            return '::';
        elseif( $this->isObject()
                && is_string( $this->method ))
            return '->';

        return '';
    }

    function isType( $typeName )
    {
        $typeName = strtolower( $typeName );

        $targetType = strtolower( $this->getTargetType() );

        return ( $typeName == $targetType ||
                 is_subclass_of( $targetType, $typeName))
                  ? true
                  : false;
    }

    function getTargetType()
    {
        if( $this->isStatic() )
            return $this->target;

        return get_class( $this->target );
    }

    function __invoke()
    {
        if( !$this->callable() )
            throw new \Core\Exception('Delegate method not found: '.$this->getTargetType().$this->getOperator(). $this->method.'()' );

        $arguments = func_get_args();

        if( $this->isStatic() )
            return self::invokeStatic( $this->target, $this->method, $arguments );
        elseif( $this->isObject() )
            if( is_null( $this->method ))
                $this->invokeObject( $arguments );

        return $this->invoke( $this->method, $arguments );
    }

    private function invoke( $method, array $arguments )
    {
        $target = $this->target;

        switch( count( $arguments ))
        {
            case 0:
                return $target->$method();
            case 1:
                return $target->$method( $arguments[0] );
            case 2:
                return $target->$method( $arguments[0],
                                         $arguments[1] );
            case 3:
                return $target->$method( $arguments[0],
                                         $arguments[1],
                                         $arguments[2] );
            case 4:
                return $target->$method( $arguments[0],
                                         $arguments[1],
                                         $arguments[2],
                                         $arguments[3] );
        }

        return $this->invokeCallback( $arguments );
    }

    private function invokeObject( array $arguments )
    {
        $target = $this->target;

        switch( count( $arguments ))
        {
            case 0:
                return $target();
            case 1:
                return $target( $arguments[0] );
            case 2:
                return $target( $arguments[0],
                                $arguments[1] );
            case 3:
                return $target( $arguments[0],
                                $arguments[1],
                                $arguments[2] );
            case 4:
                return $target( $arguments[0],
                                $arguments[1],
                                $arguments[2],
                                $arguments[3] );
        }

        return $this->invokeCallback( $arguments );
    }

    static function invokeStatic( $target, $method, array $arguments )
    {
        switch( count( $arguments ))
        {
            case 0:
                return $target::$method();
            case 1:
                return $target::$method( $arguments[0] );
            case 2:
                return $target::$method( $arguments[0],
                                         $arguments[1] );
            case 3:
                return $target::$method( $arguments[0],
                                         $arguments[1],
                                         $arguments[2] );
            case 4:
                return $target::$method( $arguments[0],
                                         $arguments[1],
                                         $arguments[2],
                                         $arguments[3] );
        }

        return $this->invokeCallback( $arguments );
    }

    function invokeCallback( array $arguments )
    {
        return call_user_func_array( $this->toCallback(), $arguments );
    }

    function __call( $method, $arguments )
    {
        if( !is_array( $arguments ))
            $arguments = array();

        return $this->invoke( $method, $arguments );
    }
}