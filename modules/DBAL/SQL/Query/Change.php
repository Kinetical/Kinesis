<?php
namespace DBAL\SQL\Query;

class Change extends \DBAL\Query\Node
{
	//CHANGE  `test`  `test` INT( 10 ) NOT NULL
	function create( $data )
	{
		if( is_array( $data ))
		{
			$this['oldName'] = $data[0];
			$this['attribute'] = $data[1];
		}

		$this->QueryBuilder->Nodes['alter']->addChild( $this );
		$attrNode = new SQLQueryAttribute( $this->QueryBuilder, $this );
		$attrNode->create( $this['attribute'] );

		return false;
	}

	function open()
	{
		//echo 'changing '.$this['oldName']."<br/>\n";
		$sql = 'CHANGE `'.$this['oldName'].'` ';
		return $sql;
	}
}