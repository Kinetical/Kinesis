<?php
namespace DBAL\SQL\Query;

class Create extends \DBAL\Query\Model\Node
{

	function create( $data )
	{
		if( parent::create( $data ))
		{
			foreach( $this->Entity->Attributes as $attribute )
			{
				$sql = new SqlQueryAttribute( $this->QueryBuilder, $this );
				$sql->create( $attribute );
			}
			$this->Resource->Stream = new SQLStream( Stream::WRITE );
			return true;
		}
			throw new Exception('Entity is without attributes');
	}

	function open()
	{
		$sql  = "CREATE TABLE \n";
		$sql .= $this->Entity->InnerName;
	//if( $this['Alias'] !== null )
		//$sql .= " AS " . $this['Alias'];
		$sql .= "\n(";

		return $sql;
	}

	function close()
	{
		if($this->Entity->PrimaryKey instanceof SQLAttribute )
			$sql = 'PRIMARY KEY ('.$this->Entity->PrimaryKey->InnerName.')';

		$sql .= ")";
		return $sql;
		// TODO:: append connection ENGINE;
	}
}

