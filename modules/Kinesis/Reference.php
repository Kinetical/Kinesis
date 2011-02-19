<?php
namespace Kinesis;

abstract class Reference
{
    public $Parameter;
    public $Container;
    public $Type;

    private $_cache = array();

    function __construct( $obj, Parameter $parameter = null )
    {
        $this->Container = $obj;
        $this->Parameter = $parameter;

        if( method_exists( $this, 'initialise') )
            $this->initialise();
    }

    protected function overload( $method, array $args = null, $statement = null )
    {
        $expression = new Task\Statement\Expression( $this, $method, $this->_cache );

        return $expression( $statement, $args );
    }

    function __destruct()
    {
        unset( $this->Container );
        unset( $this->Type );
        unset( $this->Parameter );
    }
}