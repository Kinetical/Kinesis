<?php
namespace Kinesis\Task;

abstract class Factory extends \Kinesis\Task
{
    public $Namespace;

    function __construct( $namespace = null )
    {
        if( is_string( $namespace ))
            $this->Namespace = $namespace;

        parent::__construct();
    }
}