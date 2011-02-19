<?php
namespace Kinesis\Task\Statement;

abstract class Handler extends \Kinesis\Task\Statement
{
    public $Statement;

    function __construct( $statement = null )
    {
        $this->Statement = $statement;
    }

    function __invoke()
    {
        if( func_num_args() > 0 )
            $args = $this->parse( func_get_args() );
        else
            $args = array();

        if( is_callable( $this->Statement ) )
            return call_user_func_array( $this->Statement, $args );

        return $this->execute( $args );
    }
}
