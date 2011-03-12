<?php
namespace DBAL\Data\Binding;

use \Util\Interfaces as I;

class Filter extends \DBAL\Data\Mapping\Filter
{
    protected $bindings;
    
    function __construct( array $params = array(), array $mapping = array() )
    {
        $this->bindings = new \DBAL\Data\Binding( array(), $this );
        
        parent::__construct( $params, $mapping );
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
        return $this->Parameters['BindingProperty'];
    }

    function setBindingProperty( $property )
    {
        $this->Parameters['BindingProperty'] = $property;
    }

    function getBindingKey()
    {
        return $this->Parameters['BindingKey'];
    }

    function setBindingKey( $key )
    {
        $this->Parameters['BindingKey'] = $key;
    }

    function getMappingProperty()
    {
        return $this->Parameters['MappingProperty'];
    }

    function setMappingProperty( $property )
    {
        $this->Parameters['MappingProperty'] = $property;
    }

    function getMappingKey()
    {
        return $this->Parameters['MappingKey'];
    }

    function setMappingKey( $key )
    {
        $this->Parameters['MappingKey'] = $key;
    }

    function match( $subject )
    {
        //if( $this->bindings->exists( $subject ))
          //  return $this->bindings[ $subject ];
        
        $bind = parent::match( $subject );
        
        if( $bind === false )
        {
            if( $this->Parameters->exists('BindingProperty') )
            {
                $bind = $subject->{$this->getBindingProperty()};
            }
            elseif( $this->Parameters->exists('BindingKey') &&
                    ( is_array( $subject ) ||
                      $subject instanceof ArrayAccess ))
            {
                $bind = $subject[ $this->getBindingKey() ];
            }
            else
            {
                $bind = get_class( $subject );
            }
            
            if( !is_null( $bind ))
                return $this->bindings[ $subject ] = $bind;
        }
        elseif( is_string( $subject ) && 
                array_key_exists( $subject, $this->matches ))
                return $this->matches[ $subject ];
        

        return false;
    }
    
    protected function getBindingClass( $subject )
    {
        $match = $this->match( $subject );
        if( is_string( $match ))
            return $this->mapping[ $match ];
    }

    protected function execute( array $params = null )
    {
        
        $subject = $params['input'];

        if( is_scalar( $subject ) && 
            $this->bindings->exists( $subject ))
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

        if( $this->Parameters->exists('MappingProperty'))
            $mapping = $subject->{$this->getMappingProperty()};
        elseif( $this->Parameters->exists('MappingKey') &&
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