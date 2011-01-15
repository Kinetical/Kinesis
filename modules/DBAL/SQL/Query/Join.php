<?php
namespace ORM\Query\Node\SQL;

class SQLQueryJoin extends \ORM\Query\QueryNode
{
	function create( $data )
	{
		$this['joinTable'] = $data[0];
		$this['association'] = $data[1];

		if( $this->EntityNode !== null)
			$this->EntityNode->addChild( $this );

		return false;
	}

	function open()
	{
		$sql  = "JOIN \n";
		$sql .= $this['joinTable']->InnerName .' AS ' . $this['joinTable']->Alias;
		$sql .= "\n ON ";
		$sql .= $this['joinTable']->Alias.'.'.$this->Entity->InnerName.' = ';
		$sql .= $this->Entity->Alias.'.'.$this->Entity->PrimaryKey->InnerName;

		return $sql;
	}
}