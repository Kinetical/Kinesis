<?php
namespace Kinesis\Parameter\Object\Method;

use Util\Interfaces as I;

class Factory extends \Kinesis\Parameter
{
    private $_class;
    
    protected function reflect( $class = null )
    {
        if( !is_null( $class ) &&
            class_exists( $class ))
            $this->_class = new \ReflectionClass( $class );
        
        if( $this->_class instanceof \ReflectionClass )
            return $this->_class;
        
        return false;
    }
    
    protected function resolve( $className, &$ref )
    {
        if( array_key_exists( 'Namespace', $ref->Parameters ))
            $className = $ref->Parameters['Namespace'].'\\'.ucfirst($className);
        
        return $className;
    }
    
    function call( $name, array $arguments, &$ref )
    {
        $classPath = $this->resolve( $name, $ref );
        
        if( ( $class = $this->reflect( $classPath )) !== false )
        {
            $cons = $class->getConstructor();
            $params = $cons->getParameters();
            
            foreach( $params as $param )
            {
                if( !array_key_exists( $param->getPosition(), $arguments ) )
                {
                    if( $param->isDefaultValueAvailable() )
                        $arguments[$param->getPosition()] = $param->getDefaultValue();
                    elseif( $param->allowsNull() )
                        $arguments[$param->getPosition()] = null;
                }
            }
            
            if( ( $k = $cons->getNumberOfParameters() - 1 ) == 0 )
                  $k = count( $arguments );
            
            $arguments[ $k ] = $ref;
            
            $object = $class->newInstanceArgs( $arguments );
            
            
            if( $object instanceof I\Nameable )
                $object->setName( $class->getShortName() );

            return $object;
        }

        return null;
    }
}