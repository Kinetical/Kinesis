<?php
namespace DBAL\Data\Filter;

class Entity extends Model
{
    function __construct()
    {
        $params = array( 'BindingProperty' => 'Name',
                         'MappingProperty' => 'Attributes');

        $mapping = array( 'entity' => 'DBAL\Data\Entity',
                          'entity.name' => 'Name',
                          'entity.behavior' => 'Behaviors',
                          'attribute' => 'DBAL\Data\Entity\Attribute',
                          'attribute.name' => 'Name',
                          'attribute.type' => 'DataType',
                          'one-one' => 'DBAL\Data\Entity\Relationship\OneToOne',
                          'one-many' => 'DBAL\Data\Entity\Relationship\OneToMany',
                          'many-many' => 'DBAL\Data\Entity\Relationship\ManyToMany',
                          'many-one' => 'DBAL\Data\Entity\Relationship\ManyToOne',
                          '*-*.name' => 'Name',
                          '*-*.type' => 'Association',
                          '*-*.entity' => 'EntityName',
                          '*-*.mappedBy' => 'MappedBy',
                          '*-*.inversedBy' => 'InversedBy');
        
        $signatures = array( 'entity' => array('name'),
                             'attribute' => array('name') );

        parent::__construct( $params, $mapping, $signatures );
    }

    protected function map( $mappedObject, $subject )
    {
        if( $mappedObject instanceof \DBAL\Data\Entity\Relationship )
        {
            $mappedObject->setName( $subject->Attributes['name']);
            $this->model->Relations->add( $mappedObject );
        }
        
        return parent::map( $mappedObject, $subject );
    }
}