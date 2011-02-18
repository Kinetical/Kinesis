<?php
namespace Kinesis\Event;

class Handler
{
    public $Listener;
    public $Method;

    function __construct( $obj, $method )
    {
        $this->Listener = $obj;
        $this->Method = $method;
    }
}
