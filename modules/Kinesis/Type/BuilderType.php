<?php
namespace Kinesis\Type;

use Kinesis\Parameter\Object as Object;

class BuilderType extends TaskType
{
    function __construct()
    {
        parent::__construct();
        $this->behaviors[] = new Object\Method\Chain('Tasks');
    }
}
