<?php
class Delegate
{
    public $Method;
    public $Target;

    function __construct( $target, $method = null )
    {
        $this->Target = $target;
        $this->Method = $method;
    }

    function isStatic()
    {
        return is_string( $this->Target );
    }

    function isObject()
    {
        return is_object( $this->Target );
    }

    function toCallback()
    {
        if( is_string( $this->Method ))
            if( $this->isStatic() )
                return $this->Target.'::'.$this->Target;
            else
                return array( $this->Target, $this->Method );

        return $this->Target;
    }

    function callable()
    {
        return is_callable( $this->toCallback() );
    }

    function __invoke()
    {
        if( !$this->callable() )
            return null;

        $arguments = func_get_args();

        if( $this->isStatic() )
            return self::invokeStatic( $this->Target, $this->Method, $arguments );
        elseif( $this->isObject() )
            if( is_null( $this->Method ))
                $this->invokeObject( $arguments );

        return $this->invoke( $this->Method, $arguments );
    }

    private function invoke( $method, array $arguments )
    {
        $target = $this->Target;

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
        $target = $this->Target;

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
}