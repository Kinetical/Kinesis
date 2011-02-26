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
        $params = $this->Parameters;
        $params['Query'] = $this;
        return new \Kinesis\Task\Builder( $params );
    }

    function setBuilder( \Kinesis\Task\Builder $builder )
    {
        $this->builder = $builder;
    }

    function build()
    {
        return $this->Builder;
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

        return $this->execute();
    }
}