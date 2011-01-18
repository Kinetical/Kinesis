<?php
namespace DBAL\Data;

class Iterator extends \IO\Stream\Iterator
{
    function __construct( array $inputBuffer = array(), \IO\Stream\Handler $handler, $callback = null )
    {
        parent::__construct( $inputBuffer, $handler, $callback );
    }
}