<?php
namespace DBAL\SQL\Data\Entity\Relationship;

class Mapper extends \Core\Component\Mapper
{
	function __construct()
	{

	}

	function map( \ORM\Entity\SQLEntity $entity )
	{
            $relations = $entity->getRelations();

		if( count($relations) > 0 )
		foreach( $relations as $relation )
		{

			if( !($relation instanceof \ORM\Entity\Relationship\ManyToManyRelationship)
				&& $relation->isLocalReference() )
			{
				if( !$entity->hasAttribute( $relationName = $relation->getName() ))
				{
					$attr = new \ORM\Entity\SQLAttribute( $relationName, 'integer' );
					$attr->setRelation( $relation );
					$entity->addAttribute( $attr );
				}
			}
		}

                $attributes = $entity->getAttributes();

		if( count($attributes) > 0 )
			foreach( $attributes as $attribute )
				if( $attribute->hasRelation() )
                                {
                                    $relation = $attribute->getRelation();
                                    if( $relation->isLocalReference()
					&& ! $entity->hasRelation( $relation->getName() ))
						$entity->addRelation( $relation );
                                }
					

		return $entity;
	}
}