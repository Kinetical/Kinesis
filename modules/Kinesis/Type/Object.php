<?php
namespace Kinesis\Type;

use Kinesis\Parameter as Parameter;

class Object
{
    protected $behaviors = array();

    function __construct()
    {
        $this->behaviors[] = new Parameter\Object\Property();
        $this->behaviors[] = new Parameter\Object\Property\Method();
    }

    function roles()
    {
        return $this->behaviors;
    }
}
