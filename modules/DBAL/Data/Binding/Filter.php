<?php
namespace DBAL\Data\Binding;

class Filter extends \DBAL\Data\Mapping\Filter
{
    protected $bindings;

    function initialize()
    {
        parent::initialize();

        $this->bindings = new \DBAL\Data\Binding( array(), $this );
    }

    function getBindings()
    {
        return $this->bindings;
    }

    function setBindings( array $bindings )
    {
        $this->bindings->merge( $bindings );
    }

    function getBindingProperty()
    {
        return $this->parameters['BindingProperty'];
    }

    function setBindingProperty( $property )
    {
        $this->parameters['BindingProperty'] = $property;
    }

    function getMappingProperty()
    {
        return $this->parameters['MappingProperty'];
    }

    function setMappingProperty( $property )
    {
        $this->parameters['MappingProperty'] = $property;
    }

    function match( $subject )
    {
        if( $this->bindings->exist( $subject ))
            return $this->bindings[ $subject ];
        
        $bind = parent::match( $subject );
        

        if( $bind == false )
        {
            if( $this->parameters->exists('BindingProperty') )
                $bind = $subject->{$this->getBindingProperty()};
            else
                $bind = get_class( $subject );

            return $this->bindings[ $subject ] = $bind;
        }
        elseif( is_string( $subject ) && 
                array_key_exists( $subject, $this->matches ))
                return $this->matches[ $subject ];

        return false;
    }
    
    protected function getBindingClass( $subject )
    {
        return $this->mapping[ $this->match( $subject ) ];
    }

    protected function execute( array $params = null )
    {
        $subject = $params['input'];

        if( $this->bindings->exists( $subject ))
            return $this->bindings[ $subject ];

        $bindingClass = $this->getBindingClass( $subject );

        if( get_class( $subject ) == $bindingClass )
            return $subject;

        $mappedObject = new $bindingClass;
        //$mappedObject->Type->setPersistenceObject( $subject );
        $this->map( $mappedObject, $subject );

        $this->mapping->insert( $subject->Oid, $mappedObject );

        return $mappedObject;
    }

    protected function map( $mappedObject, $subject )
    {
        $bindingClass = $this->match( $subject );

        $mappingProperty = $this->getMappingProperty();
        if( !is_null( $mappingProperty ))
            $mapping = $subject->{$mappingProperty};
        else
            $mapping = $subject;

        foreach( $mapping as $key => $value )
            if( is_string( $key ) )
            {
                $bindingField = $bindingClass.'.'.$key;
                
                if( $this->mapping->exists( $bindingField ))
                {
                    if( ($match = $this->match( $bindingField )) !== false )
                        $boundField = $match;
                    else
                        $boundField = $this->mapping[ $bindingField ];

                    if( !is_null( $boundField ) &&
                        property_exists( get_class( $mappedObject ), $boundField ) )
                        $mappedObject->$boundField = $value;
                }
            }
    }
}