<?php
namespace Kinesis\Task\Statement\Delegate;

abstract class Route extends \Kinesis\Task\Statement\Delegate
{
    public $Parameter;
    
    function __construct( $reference, \Kinesis\Parameter $parameter = null )
    {
        if( is_null( $parameter ) &&
            $this->Reference->Parameter instanceof \Kinesis\Parameter )
            $this->Parameter = $this->Reference->Parameter;
        else
            $this->Parameter = $parameter;
        
        parent::__construct( $reference );
    }
    
    function execute()
    {
        if( func_num_args() > 0 )
            return parent::execute();
        
        $ref = $this->Reference;
        
        $this->Parameter->Reference = $this->Parameters['Source'];
        $this->Reference = $this->Parameter;

        $value = parent::execute();
        $this->Reference = $ref;

        return $value;
    }
}
