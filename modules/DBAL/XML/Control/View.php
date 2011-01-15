<?php
namespace DBAL\XML\Control;

class View extends \DBAL\XML\Configuration\View
{
    function __construct( $controlNames = null )
    {
        parent::__construct('control', $controlNames );
    }

    function prepare()
    {
        parent::prepare();

        $this->getCommand()->getResource()->addMapper( new \ORM\Mapper\ControlTypeMapper() );
    }
}