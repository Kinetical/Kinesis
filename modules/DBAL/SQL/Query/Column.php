<?php
namespace DBAL\SQL\Query;

class Column extends \DBAL\Query\Node
{
	function create( $data )
	{
		// TODO: IF $data is SQLAttribute then populate from that
		$this['InnerName'] = $data[0];
		$this['Type'] = $data[1];
		$this['Default'] = $data[2];

		array_shift( $data );array_shift( $data );array_shift( $data );
		$flags = $data;
		if( !is_array( $flags ))
			$flags[] = $flags;
		$this['Flags'] = $flags;

		return parent::create();
	}

	function open()
	{
		$results  = $this['InnerName'];
		$results .= $this['Type'];//TODO: DataType class

		foreach( $this['Flags'] as $flag )
			if( $flag == EntityAttribute::AutoIncrement )
				$results .= ' auto_increment';
			elseif( $flag == EntityAttribute::NotNull )
				$results .= ' NOT NULL';

		if( $this['Default'] !== null )
			$results .= 'default '.$this['Default'];

		$results .= ",\n";

		return $results;
	}
}