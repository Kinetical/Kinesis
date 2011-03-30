<?php
namespace Kinesis;

abstract class Task extends Component
{
    /**
     * Implemented for various possibly disparate tasks
     */
    abstract protected function execute();

    /**
     * Catch or ensure arguments exist
     * @return mixed Task execution result
     */
    function __invoke()
    {
        if( func_num_args() > 0 )
            $args = func_get_args();
        else
            $args = array();

        return $this->execute( $args );
    }
}