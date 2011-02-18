<?php
namespace Kinesis\Parameter\Object;

class Copy extends \Kinesis\Parameter
{
    function copy( &$ref )
    {
        foreach( $this->Type as $key => $value )
        {
            $this->Reference->$key = $value;
        }
    }
}