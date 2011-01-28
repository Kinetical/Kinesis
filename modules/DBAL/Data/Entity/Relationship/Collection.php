<?php
namespace DBAL\Data\Entity\Relationship;

class Collection extends \Util\Collection\Dictionary
{
    protected $entity;

    function __construct( \DBAL\Data\Entity $entity, array $array = array(), $type = null )
    {
        $this->setEntity( $entity );

        if( is_null( $type ))
            $type = 'DBAL\Data\Entity\Relationship';

        parent::__construct( $array, $type );
    }

    function getEntity()
    {
        return $this->entity;
    }

    function setEntity( \DBAL\Data\Entity $entity )
    {
        $this->entity = $model;
    }
}