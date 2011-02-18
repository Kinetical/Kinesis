<?php
namespace Kinesis\Statement\Delegate;

class Intercept extends \Kinesis\Statement\Delegate
{
    function execute()
    {
        if( method_exists( $this->Reference, $this->Method ) &&
            !is_null( $value = parent::execute()))
            return $value;

        $ref = $this->Reference;
        $this->Reference = $this->Reference->Parameter;
        $this->Reference->Reference = $this->Source;

        $value = parent::execute();
        $this->Reference = $ref;

        return $value;
    }
}
