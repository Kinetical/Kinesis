<?php
namespace DBAL\Data\Entity\Relationship;

class OneToOne extends \DBAL\Data\Entity\Relationship
{
	function getQuery( RelationshipLoader $loader )
	{
		return Query::build( $this->Entity->InnerName, Query::HYDRATE_SCALAR )
					->select()
					->from( $this->Entity )
					->where( $this->Entity->PrimaryKey->InnerName, $loader->Source->Data[$this->Name]);
	}
}