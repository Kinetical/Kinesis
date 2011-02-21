<?php
namespace Kinesis\Task;

abstract class Node extends \Kinesis\Task
{
    public $Parent;
    public $Children = array();

    function __construct( array $params = null, \Kinesis\Task $parent = null )
    {
        $this->Parent = $parent;

        parent::__construct( $params );
    }
    
    protected function execute()
    {
        if( count( $this->Children ) > 0 )
        {
            $dispatcher = new \Kinesis\Dispatcher();
            return $dispatcher( $this->Children );
        }
        
        return null;
    }
}