<?php
namespace Kinesis;

abstract class Task extends Component
{
    /**
     * Implemented differently for various tasks
     */
    abstract protected function execute();

    /**
     * Catch or ensure invoke arguments
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