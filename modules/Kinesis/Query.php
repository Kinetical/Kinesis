<?php
namespace Kinesis;

abstract class Query extends Task implements \IteratorAggregate
{
    protected $builder;
    protected $iterator;

    function getBuilder()
    {
        return $this->builder;
    }

    function getDefaultBuilder()
    {
        if( array_key_exists('BuilderClass', $this->Parameters ))
            $builderClass = $this->Parameters['BuilderClass'];
        else
            $builderClass = 'Kinesis\Task\Builder';
        
        $builder = new $builderClass( array() );
        
        return $builder;
    }

    function setBuilder( \Kinesis\Task\Builder $builder )
    {
        if( array_key_exists( 'Namespace', $builder->Parameters ))
            $this->Parameters['Namespace'] = $builder->Parameters['Namespace'];
        
        if( !array_key_exists('Namespace', $this->Parameters ) )
            $this->Parameters['Namespace'] = get_class( $builder );
        
        $builder->setComponent( $this );
        $this->builder = $builder;
    }

    function build()
    {
        if( is_null( $this->builder ))
            $this->setBuilder( $this->getDefaultBuilder() );
        return $this->builder;
    }
    
    protected function assemble()
    {
        if( ($builder = $this->builder) instanceof Task )
             return $builder();
        
        return null;
    }
    
    function __toString()
    {
        return $this->assemble();
    }

    function getIterator()
    {
        if( is_null( $this->iterator ))
            $this->iterator = $this->getDefaultIterator();

        return $this->iterator;
    }

    abstract protected function getDefaultIterator();
    abstract protected function resolve();
    
    function __invoke()
    {
        $this->assemble();
        
        if( ($this->resolve()) == false )
            return null;

        $result = $this->execute();
        $this->clear();
        return $result;
    }
    
    protected function clear()
    {
        $this->iterator = null;
    }
}