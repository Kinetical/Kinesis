<?php
namespace Kinesis\Task;

/**
 * Instantiates objects using reflection
 * Class names are resolved either internally or with a filter
 */
class Factory extends \Kinesis\Task
{
    private $_filter;
    
    function initialise()
    {
        if( !$this->hasNamespace() )
            $this->Parameters['Namespace'] = 'Object\Type';
        if( array_key_exists( 'Mapping', $this->Parameters ) )
            $this->_filter = new \DBAL\Data\Mapping\Filter( $this->Parameters, 
                                                    $this->getMapping() );
    }
    
    function getNamespace()
    {
        return $this->Parameters['Namespace'];
    }
    
    function hasNamespace()
    {
        return array_key_exists('Namespace', $this->Parameters );
    }
    
    function getMapping()
    {
        return $this->Parameters['Mapping'];
    }
    
    function isMapped()
    {
        return !is_null( $this->_filter );
    }
       
    /**
     * resolves a class name from \Kinesis\Parameter
     * pre-pends local namespace parameter
     * @param \Kinesis\Parameter $parameter
     * @return string a class name
     */
    protected function getClassName( \Kinesis\Parameter $parameter )
    {
        $className = $parameter->Name;
        
        if( $this->isMapped() )
            return $this->map( $className );
        
        if( $this->hasNamespace() )
            $className = $this->getNamespace().'\\'.$className;
        
        return $className;
    }
    
    /**
     * uses mapping filter to determine class names
     * @param string $name a class short name
     * @return string a qualified class name
     */
    private function map( $name )
    {
        $filter = $this->_filter;
        return $filter( array('input' => strtolower( $name ) ));
    }
    
    /**
     * resolves valid class names and instantiates an object
     * @param array $arguments Accepts a \Kinesis\Parameter or string
     *        and an array of arguments to pass into constructor
     * @return mixed new instance of class 
     */
    protected function execute( array $arguments )
    {
        $param = $arguments[0];
        if( is_string( $param ) )
            if( $this->isMapped() )
                $className = $this->map( $param );
            else
                $className = $param;
        elseif( $param instanceof \Kinesis\Parameter )
            $className = $this->getClassName( $param );
        
        $class = \Kinesis\Type::reflect( $className );
        
        return $class->newInstanceArgs( $arguments[1] );
    }
}