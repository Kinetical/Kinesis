<?php
namespace DBAL\SQL\Query;

class Update extends \DBAL\Query\Model\Node
{
	function create( $data )
	{
		$this->Resource->Stream = new SQLStream( Stream::WRITE );

		return parent::create( $data );
	}

	function open()
	{
		$sql  = "UPDATE \n";
		$sql .= $this->Entity->InnerName . ' AS ' . $this->Entity->Alias;
		$sql .= "\n";

		return $sql;
	}
}