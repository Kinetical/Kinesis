<?php
namespace Kinesis\Type\Object;

use Kinesis\Parameter\Object as Object;

class Builder extends Task
{
    function __construct()
    {
        $this->behaviors[] = new Object\Method\Tree('Children');
        parent::__construct();
    }
}
