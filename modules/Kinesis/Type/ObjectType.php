<?php
namespace Kinesis\Type;

use Kinesis\Parameter\Object as Object;

class ObjectType
{
    protected $behaviors = array();

    function __construct()
    {
        $this->behaviors = array( new Object\Property(),
                                  new Object\Property\Method() );
    }

    function roles()
    {
        return $this->behaviors;
    }
}
