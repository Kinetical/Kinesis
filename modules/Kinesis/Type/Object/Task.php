<?php
namespace Kinesis\Type\Object;


use Kinesis\Parameter\Object as Object;

class Task extends Component
{
    function __construct()
    {
        parent::__construct();
        
        $this->behaviors[] = new Object\Callback();
        $this->behaviors[] = new Object\Method\Node();
    }
}
