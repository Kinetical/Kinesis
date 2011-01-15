<?php
namespace DBAL\Data;

class Iterator extends \IO\Stream\Iterator
{
    function __construct( array $inputBuffer = array(), Wrapper $wrapper, $callback = null )
    {
        parent::__construct( $inputBuffer, $wrapper, $callback );
    }
}