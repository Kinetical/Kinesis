<?php 
namespace Core\Object;

class ReflectionClass extends \ReflectionClass 
{
	private $_persistenceObject;
	private $_persistenceType;
	private $_events = array();
		
	function setPersistenceObject( \Core\Object $object )
	{
		$this->_persistenceObject = $object->Oid;
		$this->_persistenceType = $object->Type->name;
	}
	
	function getPersistenceObject()
	{	
            $loader = \Core::getInstance()->getLoader();
            if( $loader->has( $this->_persistenceObject ))
                return $loader->get( $this->_persistenceObject );
	}
	
	
	function getPersistenceType()
	{
		return $this->_persistenceType;
	}
	
	function isPersisted()
	{
		return ( $this->_persistenceObject !== null ) ? true : false;
	}
	
	function isPersistedBy( $type )
	{
		return (is_subclass_of($this->_persistenceType, $type)) ? true : false;
	}
	
	function persist( \Core\Object $object )
	{
		$this->setPersistenceObject( $object );
	}
	
	function getEvents()
	{
		return $this->_events;
	}
	
	function hasEvent( $event )
	{
		if( $event instanceof \Core\Event )
			$event = $event->getName();
			
		return array_key_exists( $event, $this->_events );
	}

        function hasEvents( array $events )
        {
            foreach( $events as $evt )
                if( !$this->hasEvent( $evt ))
                    return false;

            return true;
        }

	
	function getEvent( $event )
	{
            return $this->_events[ $event ];
	}
	
	function setEvents( $events )
	{		
            foreach( $events as $evt )
                    $this->addEvent( $evt );
	}
	
	function addEvent( \Core\Event $event )
	{
            $this->_events[ $event->getName() ] = $event;
	}

        private function hasIntercept( $obj, $propertyName, $prefix )
        {
            $methodName = $prefix.ucfirst($propertyName);

            
            if( method_exists( $obj, $methodName))
                 return $methodName;

            return false;
        }

        function hasGetIntercept( $obj, $propertyName )
        {
            return $this->hasIntercept( $obj, $propertyName, 'get');
        }

        function hasSetIntercept( $obj, $propertyName )
        {
            return $this->hasIntercept( $obj, $propertyName, 'set');
        }

        function hasPropertyRelation( $obj, $propertyName )
        {
            if( $this->isPersisted()
                 && $this->isPersistedBy('EntityObject')
                 && $this->getPersistenceObject()->hasRelation( $propertyName ) )
                    return true;

            return false;
        }

        function hasProperty( $obj, $propertyName )
        {
            if( $this->hasGetIntercept($obj, $propertyName)
                || $this->hasSetIntercept($obj, $propertyName)
                || $this->hasEvent($propertyName)
                || $this->hasPropertyRelation( $obj, $propertyName ) )
                return true;
            
            return parent::hasProperty( $propertyName );
        }

        function hasPropertyData( $obj, $propertyName )
        {
            if( count( $obj->Data ) > 0
                && array_key_exists( $propertyName, $obj->Data )
                && $data[$propertyName] !== null )
                return true;

            return false;
        }

        function hasPreProcess( $methodName )
        {
            return $this->hasMethod( 'pre'.$methodName );
        }

        function hasPostProcess( $methodName )
        {
            return $this->hasMethod( 'post'.$methodName );
        }

        function getPropertyValue( $obj, $propertyName )
        {
            if( $propertyName instanceof \Core\Type\IObject )
                $propertyName = $propertyName->Oid;

            if( ($methodName = $this->hasGetIntercept( $obj, $propertyName) ) !== false )
                    return $obj->$methodName();
            if( $this->hasEvent($propertyName))
                return $this->getEvent( $propertyName );

            if(  $this->hasPropertyRelation( $obj, $propertyName )
                 && ( ($this->hasPropertyData( $obj, $propertyName )
                          && !($obj->Data[$propertyName] instanceof \Core\Object))
                          || ( !array_key_exists( $propertyName, $this->Data ) ) ) )
                {
                    $lazyLoader = new DeferredLoader( $this, $propertyName );
                    return $lazyLoader->load( null );
                }

             return null;
        }

        function setPropertyValue( $obj, $propertyName, $value )
        {
            if( $value === null )
            {
                unset($obj->Data[$propertyName]);
            }

            if( $propertyName instanceof \Core\Type\IObject )
            $propertyName = $propertyName->Oid;

            if( ($methodName = $this->hasSetIntercept( $obj, $propertyName) ) !== false  )
                 $obj->$methodName( $value );
        }

        function invoke( $obj, $methodName, array $arguments = array() )
        {
            if( strtolower(substr( $methodName, 0, 3)) == 'has')
            {
                $name = substr( $methodName, 3 );
                if( !empty( $obj->Data[$name] )
                        &&  $obj->__isset( strtolower($name) ))
                        return true;

                return false;
            }

            if( $this->hasEvent( $methodName ) )
                return $obj->$methodName->notify( $arguments );

            return null;
        }
        
        private function preProcess( $obj, $methodName, array $arguments = null )
        {
            $presult = call_user_func_array( array( $obj, 'pre'.$methodName ), $arguments );
            if( is_bool( $presult )
                && $presult !== false )
                return true;

            return false;
        }

        private function postProcess( $obj, $methodName, array $arguments = null )
        {
            call_user_func_array( array( $obj, 'post'.$methodName ), $arguments );
        }
}