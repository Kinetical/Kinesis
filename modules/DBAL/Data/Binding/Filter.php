<?php
namespace DBAL\Data\Binding;

use \Util\Interfaces as I;

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

    function getBindingKey()
    {
        return $this->parameters['BindingKey'];
    }

    function setBindingKey( $key )
    {
        $this->parameters['BindingKey'] = $key;
    }

    function getMappingProperty()
    {
        return $this->parameters['MappingProperty'];
    }

    function setMappingProperty( $property )
    {
        $this->parameters['MappingProperty'] = $property;
    }

    function getMappingKey()
    {
        return $this->parameters['MappingKey'];
    }

    function setMappingKey( $key )
    {
        $this->parameters['MappingKey'] = $key;
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
            elseif( $this->parameters->exists('BindingKey') &&
                    ( is_array( $subject ) ||
                      $subject instanceof ArrayAccess ))
                $bind = $subject[ $this->getBindingKey() ];
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

        if( is_null( $bindingClass ) )
            return null;
        
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

        if( $this->parameters->exists('MappingProperty'))
            $mapping = $subject->{$this->getMappingProperty()};
        elseif( $this->parameters->exists('MappingKey') &&
                ( is_array( $subject ) ||
                  $subject instanceof ArrayAccess ))
            $mapping = $subject[$this->getMappingKey()];
        else
            $mapping = $subject;

        foreach( $mapping as $key => $value )
        {
            $bindingField = $bindingClass.'.'.$key;

            if( $this->mapping->exists( $bindingField ))
            {
                if( ($boundField = $this->match( $bindingField )) == false )
                    $boundField = $this->mapping[ $bindingField ];

                if( !is_null( $boundField ) )
                    if( method_exists( $mappedObject, $boundField ))
                        $mappedObject->$boundField( $value );
                    else
                        $mappedObject->$boundField = $value;
            }
        }
    }
}