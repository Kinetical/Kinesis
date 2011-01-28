<?php
namespace DBAL\Data\Filter;

abstract class Model extends \DBAL\Data\Binding\Filter
{
    protected $model;

    protected function map( $mappedObject, $subject )
    {
        if( $mappedObject instanceof \DBAL\Data\Model )
            $this->model = $mappedObject;
        elseif( $mappedObject instanceof \DBAL\Data\Model\Attribute )
            $this->model->Attributes->add( $mappedObject );

        parent::map( $mappedObject, $subject );
    }
}