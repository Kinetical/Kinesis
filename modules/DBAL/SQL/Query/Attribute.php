<?php
namespace DBAL\SQL\Query;

class Attribute extends \DBAL\Query\Node
{
	function create( $data )
	{
		if( $data instanceof EntityAttribute )
		{
			if( $data->Length == null )
			{
				$data->Length = $data->DataType->DefaultLength;
			}
			$this['attribute'] = $data;
		}

		if( $this->QueryBuilder->hasNode('create'))
			$this->QueryBuilder->Nodes['create']->addChild( $this );

			//TODO: MUST GOTO CORRECT ALTER CHILD QUERYNODE

		return false;
	}

	function open()
	{
		$attr = $this['attribute'];

		$results .= '`'.$attr->InnerName.'`';
		$results .= ' ';
		$results .= (string)$attr->DataType;
	if( $attr->Length > 0 )
		$results .= '('.$attr->Length.')';
		$results .= $this->flags();
	if( $attr->Default !== null )
		$results .= ' default '. $attr->Default;

		if( $this->QueryBuilder->hasNode('create'))
			if( $attr->InnerName !== $this->getLastAttribute()
				|| $attr->Entity->PrimaryKey !== null )
				$results .= ',';

		return $results;
	}

	private function flags()
	{
		foreach( $this['attribute']->Flags as $flag )
			if( $flag == EntityAttribute::AutoIncrement )
				$flags .= ' auto_increment';
			elseif( $flag == EntityAttribute::NotNull )
				$flags .= ' NOT NULL';

		return $flags;
	}

	private function getLastAttribute()
	{
		$entity = $this['attribute']->Entity;
		$keys = array_keys( $entity->Attributes );
		return $keys[count($keys)-1];
	}
}