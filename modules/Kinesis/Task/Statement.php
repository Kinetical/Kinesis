<?php
namespace Kinesis\Task;

abstract class Statement extends \Kinesis\Task
{
    public $Reference;
    public $Source;

    function __construct( $reference )
    {
        $this->Reference = $reference;
    }

    function parse( array $array = null )
    {
        if( $this instanceof Statement\Delegate )
            $this->Arguments = $array;

        return $array;
    }

    function __invoke()
    {
        if( func_num_args() > 0 )
            $args = $this->parse( func_get_args() );
        else
            $args = array();

        return $this->execute( $args );
    }

}