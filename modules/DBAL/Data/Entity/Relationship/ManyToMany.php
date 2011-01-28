<?php
namespace DBAL\Data\Entity\Relationship;

class ManyToMany extends \DBAL\Data\Entity\Relationship
{
    function getQuery( Loader\RelationshipLoader $loader )
    {
        $name = $this->Association->InnerName.'_'.$this->Entity->InnerName;

        $joinTable = new JoinTableRelationship( $name,
                                                new JoinColumnRelationship($this->Association->InnerName, 'id'),
                                                new JoinColumnRelationship($this->Entity->InnerName, 'id ') );
        return $joinTable->getQuery( $loader );
    }
}