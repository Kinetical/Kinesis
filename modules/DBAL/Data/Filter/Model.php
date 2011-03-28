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
        {
            $mappedObject->setName( $subject->Attributes['name']);
            $this->model->Attributes->add( $mappedObject );
        }

        return parent::map( $mappedObject, $subject );
    }
}