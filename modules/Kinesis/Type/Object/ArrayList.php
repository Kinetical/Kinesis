<?php
namespace Kinesis\Type\Object;

use Kinesis\Parameter\Object as Object;

class ArrayList extends \Kinesis\Type\Object
{
    function __construct()
    {
        $this->behaviors[] = new Object\Property\ArrayList('Data');
        parent::__construct();
    }
}
