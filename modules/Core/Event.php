<?php
namespace Core;

use \Util\Interfaces as I;

class Event extends Object implements I\Nameable
{
	private $_listeners = array();
	private $_name;



	public function __construct( $name = null )
	{
            $this->_name = $name;

            parent::__construct();
	}

	function getName()
	{
            return $this->_name;
	}

	function setName( $name )
	{
            $this->_name = $name;
	}

	function attach( \Core\Event\Listener $obj, $methodName = null)
	{
            if( $methodName == null )
                $methodName = count( $this->_listeners );

            $this->_listeners[$methodName] = $obj;
	}

	function notify( $params )
	{
            foreach( $this->_listeners as $method => $observer )
            {
                if( !is_string( $method ))
                    $method = $this->getName();

                if( $observer->Type->hasEvent( $method ))
                         $observer->triggered( $method, $params );
            }

            return $this;
	}
}
