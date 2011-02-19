<?php
namespace Kinesis\Type;

use Kinesis\Parameter\Object as Object;

class TaskType extends ObjectType
{
    function __construct()
    {
        parent::__construct();
        $this->behaviors[] = new Object\Callback();
    }
}
