<?php
namespace Kinesis;

abstract class Task extends Object
{
    public $Parameters = array();
    public $Parent;
    public $Children = array();

    function __construct( array $params = array(), Task $parent = null )
    {
        $this->Parameters = $params;
        $this->Parent = $parent;
    }
    
    abstract protected function execute();

    function __invoke()
    {
        if( func_num_args() > 0 )
            $args = func_get_args();
        else
            $args = array();

        return $this->execute( $args );
    }

}