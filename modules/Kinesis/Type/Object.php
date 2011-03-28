<?php
namespace Kinesis\Type;

use Kinesis\Parameter as Parameter;

class Object
{
    protected $behaviors = array();

    function __construct()
    {
        //$this->behaviors[] = new Parameter\Object\Property();
        $this->behaviors[] = new Parameter\Object\Property\Method( null, $this );
    }

    function roles()
    {
        return $this->behaviors;
    }
    
    function toPrimitive( $value )
    {
        return unserialize( $value );
    }
}
