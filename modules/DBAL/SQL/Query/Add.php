<?php
namespace DBAL\SQL\Query;

class Add extends \DBAL\Query\Node
{
	//ADD  `test` INT( 1 ) NOT NULL
	function create( $data )
	{
		if( $data instanceof EntityAttribute )
		{
			$this['attribute'] = $data;
			$attrNode = new SQLQueryAttribute( $this->QueryBuilder, $this );
			$attrNode->create( $this['attribute'] );
		}

		$this->QueryBuilder->Nodes['alter']->addChild( $this );
		return false;
	}

	function open()
	{
		//echo '+column: '.$this['attribute']->InnerName."<br/>\n";
		return 'ADD ';
	}
}