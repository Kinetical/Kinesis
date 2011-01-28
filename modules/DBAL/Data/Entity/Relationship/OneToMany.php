<?php
namespace DBAL\Data\Entity\Relationship;

class OneToMany extends \DBAL\Data\Entity\Relationship
{
	function getQuery( Loader\RelationshipLoader $loader )
	{
		$joinColumn = new JoinColumnRelationship( $this->Entity, $this->Association );

		return $joinColumn->getQuery( $loader );
	}
}