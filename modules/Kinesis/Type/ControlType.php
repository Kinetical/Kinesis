<?php
namespace Kinesis\Type;

use Kinesis\Parameter\Object as Object;

class ControlType extends ObjectType
{
    function __construct()
    {
        parent::__construct();
        $this->behaviors[] = new Object\Method\Control();
    }
}
