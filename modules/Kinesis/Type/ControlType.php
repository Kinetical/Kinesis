<?php
namespace Kinesis\Type;

use Kinesis\Parameter\Object as Object;

class ControlType extends ObjectType
{
    function roles()
    {
        return parent::roles( new Object\Control() );
    }
}
