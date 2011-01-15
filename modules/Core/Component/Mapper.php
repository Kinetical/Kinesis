<?php
namespace Core\Component;

class Mapper extends \DBAL\Data\Mapper
{
    private $_property;
    //private $_mapped;
    private $_mappedTypes;
    private $_mappingProperty;

    private $_state = ClassMapper::Mapping;



    function __construct( array $mapping, $bindingProperty = null, $mappingProperty = 'Data' )
    {
            $this->_property = $bindingProperty;
            $this->_mappingProperty = $mappingProperty;

            parent::__construct( $mapping );
    }

    function initialize()
    {
        parent::initialize();

        $this->Type->addEvent( new \Core\Event('StateChanged') );
    }

    function getState()
    {
            return $this->_state;
    }

    function setState( $state )
    {
            if( is_int( $state ))
                    $this->_state = $state;
            else
                    return null;

            $this->StateChanged( $state );
    }

    function getResource()
    {
            return $this->_resource;
    }

    protected function setResource( $resource )
    {
            if( !$resource->hasMapper( $this ) )
                     $resource->addMapper( $this );
            $this->_resource = $resource;
    }

    protected function setBindingProperty( $name )
    {
            $this->_property = $name;
    }

    function getBindingProperty()
    {
            return $this->_property;
    }

    protected function setMappingProperty( $name )
    {
            $this->_mappingProperty = $name;
    }

    function getMappingProperty()
    {
            return $this->_mappingProperty;
    }

    private $_bindings;

    protected function addBinding( $subject, $binding )
    {
        if( $subject instanceof \Core\Object )
            $subject = $subject->Oid;

        return $this->_bindings[ $subject ] = $binding;
    }

    private function hasBinding( $subject )
    {
        if( $subject instanceof \Core\Object )
            $subject = $subject->Oid;

        return array_key_exists( $subject, $this->_bindings );
    }

    private function getCachedBinding( $subject )
    {
        if( $subject instanceof \Core\Object )
            $subject = $subject->Oid;

        return $this->_bindings[ $subject ];
    }

    protected function getBinding( $subject )
    {
        if( $this->hasBinding( $subject ))
                return $this->getCachedBinding( $subject );

            if( is_string( $subject ))
                    return $subject;

            if( $this->_property !== null )
                    return $this->addBinding( $subject, $subject->{$this->_property} );

            return strtolower(get_class($subject));
    }

    function getMapped()
    {
            return $this->getCache();
    }

    function add( $subject, $object = null )
    {
        if( $subject instanceof \Core\Object )
            $subject = $subject->Oid;
        
        if( $object == null )
            $object = $subject;

            parent::add( $subject, $object );
            $this->_mappedTypes[ get_class($object) ][$subject] = $object;
    }

    function getMappedByType()
    {
            $types = func_get_args();

            $returnTypes = array();
            //$mappedTypes = $this->_mappedTypes;
            foreach( $types as $type )
                    if( array_key_exists( $type, $this->_mappedTypes ))
                            $returnTypes = array_merge( $returnTypes,
                                                        $this->_mappedTypes[ $type ] );

            return $returnTypes;
    }

    protected function getBindingClass( $subject )
    {
        $mapping = $this->getMapping();
            return $mapping[ $this->getBinding( $subject ) ];
    }

    function isMapped( $name )
    {
            if( is_object( $name ))
                    $name = $this->getBinding( $name );

            if( is_string( $name )
                    && array_key_exists( $name, $this->getMapping() ))
                    return true;

            //$test = $this->matchMapping( $name );
            if( is_string( $name )
                    && array_key_exists($this->matchMapping( $name ),$this->getMapping() ) )
                    return true;

            return false;
    }

    function preMap()
    {
            return true;
    }

    function postMap()
    {
            return true;
    }

    function map( \Core\Object $subject )
    {
            if( $this->has( $subject->Oid ))
                    return $this->get[ $subject->Oid ];

            if( !$this->isMapped( $subject ))
                    return null;

            $bindingClass = $this->getBindingClass( $subject );

            if( get_class( $subject ) == $bindingClass )
                    return $subject;

            $mappedObject = new $bindingClass;
            $mappedObject->Type->setPersistenceObject( $subject );
            $this->mapData( $mappedObject, $subject );

            $this->add( $subject, $mappedObject );

            return $mappedObject;
    }

    protected function mapData( $mappedObject, \Core\Object $subject )
    {
        $bindingClass = $this->getBinding( $subject );

        $mapping = $this->getMapping();
        foreach( $subject->{$this->_mappingProperty} as $key => $value )
            if( is_string( $key ) )
            {
                $bindingField = $bindingClass.'.'.$key;

                if( $this->isMapped( $bindingField ))
                {
                    if( ($match = $this->matchMapping( $bindingField )) !== false )
                        $boundField = $mapping[ $match ];
                    else
                        $boundField = $mapping[ $bindingField ];
                    $mappedObject->$boundField = $value;
                }
                else
                    $mappedObject->Data[$key] = $value;
            }
    }
}