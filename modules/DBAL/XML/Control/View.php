<?php
namespace DBAL\XML\Control;

class View extends \DBAL\XML\Configuration\View
{
    function __construct( $controlNames = null )
    {
        parent::__construct('control', $controlNames );
    }

    function prepare( $source = null )
    {
        parent::prepare( $source );

        $source->Map->register( new \ORM\Mapper\ControlTypeMapper() );

        return $this->command;
    }
}