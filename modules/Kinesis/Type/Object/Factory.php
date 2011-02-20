<?php
namespace Kinesis\Type\Object;

use Kinesis\Parameter\Object as Object;

class Factory extends Task
{
    function __construct()
    {
        parent::__construct();
        $this->behaviors[] = new Object\Method\Factory();
    }
}
