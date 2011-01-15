<?php
namespace DBAL\Data\Entity\Relationship;

class JoinTable extends \DBAL\Data\Entity\Relationship
{
	private $_joinAttributes;
	private $_inverseJoinAttributes;

	function getJoinAttributes()
	{
		return $this->_joinAttributes;
	}

	function getInverseAttributes()
	{
		return $this->_inverseJoinAttributes;
	}

	function __construct( $name = null, $joinAttributes = null, $inverseJoinAttributes = null )
	{
		$this->Name = $name;
		if( $joinAttributes !== null
			&& !is_array( $joinAttributes ))
			$joinAttrs[] = $joinAttributes;
		elseif( is_array( $joinAttributes ))
			$joinAttrs = $joinAttributes;


		if( $inverseJoinAttributes !== null
			&& !is_array( $inverseJoinAttributes ))
			$inverseAttrs[] = $inverseJoinAttributes;
		elseif( is_array( $inverseJoinAttributes ))
			$inverseAttrs = $inverseJoinAttributes;

		$this->_joinAttributes = $joinAttrs;
		$this->_inverseJoinAttributes = $inverseAttrs;
	}

	function getQuery( RelationshipLoader $loader )
	{
		//TODO: IF JOINT TABLE DOESNT EXIST, THEN CREATE IT
		if( $loader instanceof DeferredLoader )
		{
			$joinTable = $this->getJoinTable();
			$query = Query::build( Query::SQL, $loader->Relation->Entity )
							->select()
							->from( $loader->Relation->Entity )
							->join( $joinTable, $loader->Relation->Association );

			if( $loader->Relation->isLocalReference() )
			{
				foreach( $this->_joinAttributes as $joinAttribute )
				{

					$query->where( $joinTable->Attributes[$joinAttribute->Name], $loader->Source->Data[ $joinAttribute->ReferencedAttribute ]);
				}
			}
			else
			{
				foreach( $this->_inverseJoinAttributes as $joinAttribute )
				{
					//echo $joinAttribute->Name;
					//echo "\n";
					//var_dump( $joinTable );
					//echo $joinTable->Attributes[$joinAttribute->Name]->InnerName;
					//$query->where( $joinTable->Attributes[$joinAttribute->Name], $loader->Source->Data[ $joinAttribute->ReferencedAttribute ]);
				}
			}
							//->where( $joinTable->Attributes[$this->_joinColumns->Name]->InnerName, $loader->Source->Data[$loader->Relation->Association->PrimaryKey->OuterName]);
			//echo (string)$query;

		}

		if( isset( $query ))
			return $query;
		return null;
	}

	private function getJoinTable()
	{
            $dataSet = \Core::getInstance()->getDatabase()->getDataSet();

		return $dataSet->Schematics[ $this->getName() ];
	}
}