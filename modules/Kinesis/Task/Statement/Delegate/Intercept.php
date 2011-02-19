<?php
namespace Kinesis\Task\Statement\Delegate;

final class Intercept extends Route
{
    function execute()
    {
        if( method_exists( $this->Reference, $this->Method ) &&
            !is_null( $value = parent::execute( true )))
            return $value;

        return parent::execute();
    }
}
