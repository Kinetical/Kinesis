<?php
namespace DBAL\XML\Data\Entity;

class View extends \DBAL\XML\Configuration\View
{
    function __construct( $entityNames = null )
    {
        parent::__construct('entity', $entityNames );
    }

    function prepare()
    {
        parent::prepare();

        $this->Command->Resource->addMapper( new \ORM\Mapper\SQLEntityMapper() );
    }
}