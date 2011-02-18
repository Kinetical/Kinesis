<?php
namespace Kinesis;

abstract class Statement
{
    public $Reference;
    public $Source;


    function __construct( $reference )
    {
        $this->Reference = $reference;
    }

    abstract protected function execute();
    function parse( array $array = null )
    {
        if( $this instanceof Delegate )
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