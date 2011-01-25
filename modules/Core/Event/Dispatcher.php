<?php
namespace Core\Event;

class Dispatcher extends \Core\Object
{
    protected $handlers = array();
    private $priority = array();

    function getHandlers()
    {
        return $this->handlers;
    }

    function setHandlers( array $handlers )
    {
        $this->handlers = $handlers;
    }

    function attach( $name, \Core\Object $listener , $priority = 0 )
    {
        $this->handlers[ $listener->Oid ] = new Handler( $name, $listener );
        $this->priority[ $listener->Oid ] = $priority;
    }

    function detach( \Core\Object $listener )
    {
        unset( $this->handlers[ $listener->Oid ]);
    }

    function notify( \Core\Event $event, array $params = array() )
    {
        extract( $params );
        if( !isset( $continuous ))
            $continuous = true;

        $filtering = isset( $input );

        natsort( $this->priority );

        foreach( $this->priority as $oid => $priority )
        {
            $event->setHandler( $this->handlers[$oid] );

            if( $filtering )
                $input = $event( $input );
            elseif( $event() &&
                   !$continuous )
                    $event->process();

            if( $event->processed() )
                break;
        }

        if( $filtering )
            $event->setValue( $input );

        return $event;
    }
}