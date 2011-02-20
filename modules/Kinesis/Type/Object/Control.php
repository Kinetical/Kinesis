<?php
namespace Kinesis\Type\Object;

use Kinesis\Parameter\Object as Object;

class Control extends \Kinesis\Type\Object
{
    function __construct()
    {
        parent::__construct();
        $this->behaviors[] = new Object\Method\Control();
    }
}
