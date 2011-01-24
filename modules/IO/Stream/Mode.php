<?php
namespace IO\Stream;

class Mode
{
    const Read = 'r';
    const Write = 'w';

    public $Description;

    function __construct( $mode = self::READ )
    {
        $this->Description = $mode;
    }

    function __toString()
    {
        return $this->Description;
    }

    function is( $desc )
    {
        return (strpos($this->Description, $desc ) !== false )
                ? true
                : false;
    }
}