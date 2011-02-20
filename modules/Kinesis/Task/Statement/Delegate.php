<?php
namespace Kinesis\Task\Statement;

class Delegate extends \Kinesis\Task\Statement
{
    public $Method;
    public $Arguments;

    function __construct( $reference, $method = null, array $arguments = array() )
    {
        $this->Method = $method;
        parent::__construct( $reference );
        $this->Arguments = $arguments;
    }

    function __invoke()
    {
        if( empty( $this->Arguments ) &&
            func_num_args() > 0 )
            $this->parse( func_get_args() );

        $result = $this->execute();
        $this->Arguments = array();
        return $result;
    }

    protected function execute()
    {
        $c = count( $this->Arguments );
        if( $c < 5 )
        {
            $method = $this->Method;

            switch( $c )
            {
                case 0:
                    return $this->Reference->$method();
                case 1:
                    return $this->Reference->$method( $this->Arguments[0] );
                case 2:
                    return $this->Reference->$method( $this->Arguments[0],
                                                      $this->Arguments[1] );
                case 3:
                    return $this->Reference->$method( $this->Arguments[0],
                                                      $this->Arguments[1],
                                                      $this->Arguments[2] );
                case 4:
                    return $this->Reference->$method( $this->Arguments[0],
                                                      $this->Arguments[1],
                                                      $this->Arguments[2],
                                                      $this->Arguments[3] );
            }
        }

        return $this->invokeCallback();
    }

    protected function invokeCallback()
    {
        return call_user_func_array( array( $this->Reference,
                                            $this->Method ),
                                            $this->Arguments );
    }
}