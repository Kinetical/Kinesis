<?php

    function __callStatic( $methodName, $arguments )
    {
        switch( count( $arguments ))
        {
            case 0:
                return self::$methodName();
            case 1:
                return self::$methodName( $arguments[0] );
            case 2:
                return self::$methodName( $arguments[0],
                                          $arguments[1] );
            case 3:
                return self::$methodName( $arguments[0],
                                          $arguments[1],
                                          $arguments[2] );
            case 4:
                return self::$methodName( $arguments[0],
                                          $arguments[1],
                                          $arguments[2],
                                          $arguments[3] );
            default:
                return call_user_func_array( __CLASSNAME__'::'.$methodName , $arguments );
            break;
        }
    }
    if( empty( $arguments ) )
        $this->$methodName();
    elseif( count( $arguments ) == 1 )
        return $this->$methodName( $arguments[0] );
    elseif( count( $arguments ) == 2 )
        return $this->$methodName( $arguments[0],
                                   $arguments[1] );
    elseif( count( $arguments ) == 3 )
        return $this->$methodName( $arguments[0],
                                   $arguments[1],
                                   $arguments[2] );
    elseif( count( $arguments ) == 4 )
        return $this->$methodName( $arguments[0],
                                   $arguments[1],
                                   $arguments[2],
                                   $arguments[3] );
    else
        return call_user_func_array( array( $this, $methodName ), $arguments );
}