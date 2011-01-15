<?php
namespace DBAL\SQL\Query;

class Tables extends \DBAL\Query\Node
{
	function create( $data )
	{
		if( $data instanceof SQLDatabase )
			$this['database'] = $data;

		$this->Resource->Stream = new SQLStream( Stream::READ );

		return parent::create();
	}

	function open()
	{
		$sql = 'SHOW TABLES';
		if( isset( $this['database']) )
			$sql .= ' IN '.$this['database']->InnerName;

		return $sql;
	}
}