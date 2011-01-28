<?php
namespace DBAL\Data\Filter;

class Entity extends Model
{
    protected function map( $mappedObject, $subject )
    {
        if( $mappedObject instanceof \DBAL\Data\Entity\Relationship )
            $this->model->Relations->add( $mappedObject );

        parent::map( $mappedObject, $subject );
    }
}