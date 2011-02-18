<?php
namespace Kinesis\Statement\Delegate;

class Bypass extends \Kinesis\Statement\Delegate
{
    function execute()
    {

        $ref = $this->Reference;
        $this->Reference = $this->Reference->Parameter;
        $this->Reference->Reference = $this->Source;

        $value = parent::execute();
        $this->Reference = $ref;

        return $value;
    }
}
