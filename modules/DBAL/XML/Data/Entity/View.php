<?php
namespace DBAL\XML\Data\Entity;

class View extends \DBAL\XML\Configuration\View
{
    function __construct( $entityNames = null )
    {
        parent::__construct('entity', $entityNames );
    }

    function prepare( $source = null )
    {
        parent::prepare( $source );

        $source->Map->regster( new \ORM\Mapper\SQLEntityMapper() );

        return $this->command;
    }
}