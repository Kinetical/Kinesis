<?php
namespace DBAL\SQL\Data\Entity\Relationship;

class ManyToOne extends \DBAL\SQL\Data\Entity\Relationship
{
	function getQuery( Loader\RelationshipLoader $loader )
	{
		$joinColumn = new JoinColumnRelationship( $this->Association, $this->Entity );

		$query = $joinColumn->getQuery( $loader );
		$query->Query->Hydration = \ORM\Query::HYDRATE_OBJECT;

		return $query;
	}
}