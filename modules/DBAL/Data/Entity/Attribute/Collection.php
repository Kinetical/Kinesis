<?php
namespace DBAL\Data\Entity\Attribute;

class Collection extends \DBAL\Data\Model\Attribute\Collection
{

    function __construct( \DBAL\Data\Entity $model, array $array = array(), $type = null )
    {
        if( is_null( $type ))
            $type = 'DBAL\Data\Entity\Attribute';
        parent::__construct( $model, $array, $type );
    }

    function getEntity()
    {
        return $this->getModel();
    }

    function setEntity( \DBAL\Data\Entity $entity )
    {
        $this->setModel( $entity );
    }

    function setModel( \DBAL\Data\Entity $model )
    {
        parent::setModel( $model );
    }

    function offsetSet( $offset, $attr )
    {
        if( $attr->HasFlag( \DBAL\Data\Entity\Attribute::PrimaryKey ))
            $this->model->setKey( $attribute );

        parent::offsetSet( $offset, $attr );
    }
}