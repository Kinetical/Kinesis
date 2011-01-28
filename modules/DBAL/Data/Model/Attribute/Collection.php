<?php
namespace DBAL\Data\Model\Attribute;

class Collection extends \Util\Collection\Dictionary
{
    protected $model;

    function __construct( \DBAL\Data\Model $model, array $array = array(), $type = null )
    {
        $this->setModel( $model );

        if( is_null( $type ))
            $type = 'DBAL\Data\Model\Attribute';

        parent::__construct( $array, $type );
    }

    function getModel()
    {
        return $this->model;
    }

    function setModel( \DBAL\Data\Model $model )
    {
        $this->model = $model;
    }

    function offsetSet( $offset, $attr )
    {
        $attr->setModel( $this->model );
        if( !is_null($attr->getLoadName()) )
            $this->model->setLoaderAttribute( $attr );

        parent::offsetSet( $offset, $attr );
    }
}