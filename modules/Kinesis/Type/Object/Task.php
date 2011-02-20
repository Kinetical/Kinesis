<?php
namespace Kinesis\Type\Object;


use Kinesis\Parameter\Object as Object;

class Task extends \Kinesis\Type\Object
{
    function __construct()
    {
        $this->behaviors[] = new Object\Property\Defaults();
        parent::__construct();
        
        $this->behaviors[] = new Object\Callback();
        $this->behaviors[] = new Object\Method\ArrayList('Parameters','Parameter');
        $this->behaviors[] = new Object\Method\Node();
        
        
    }
}
