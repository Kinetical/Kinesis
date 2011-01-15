<?php
namespace DBAL\SQL\Data\Entity\Relationship;

class OneToMany extends \DBAL\SQL\Data\Entity\Relationship
{
	function getQuery( Loader\RelationshipLoader $loader )
	{
		$joinColumn = new JoinColumnRelationship( $this->Entity, $this->Association );

		return $joinColumn->getQuery( $loader );
	}
}