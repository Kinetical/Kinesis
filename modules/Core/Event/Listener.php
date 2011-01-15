<?php 
namespace Core\Event;

abstract class Listener
{
    function triggered( $name, $params = null  )
    {
            //$name = $e->Event->Name;
            if( method_exists( $this, $name ))
            {
                    call_user_func( array( $this, $name), $params );
            }
    }

    protected function trigger( $name, $params = null )
    {
        //if this has event
        if( $params == null )
                $params = array();

        $event = $this->{$name};
        call_user_func( array( $event, 'notify'), $params );
    }
}