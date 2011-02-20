<?php
namespace Kinesis\Task;

abstract class Statement extends \Kinesis\Task
{
    public $Reference;

    function __construct( $reference = null )
    {
        $this->Reference = $reference;
    }

    function parse( array $array = null )
    {
        if( property_exists( get_class( $this ), 'Arguments' ) )
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