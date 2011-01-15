<?php
namespace DBAL\SQL\Data\Entity\Attribute;

class Mapper extends \DBAL\Data\Entity\Mapper
{
	function __construct()
	{

	}

	function map( \DBAL\Data\Tree\Node $node )
	{
		//TODO: MAP IF node is from SQL Entity in XML file, not raw from MYSQL
		$sqlAttribute = new SQLAttribute( $node['Field'], $this->getAttributeType( $node['Type'] ) );
		$length = $this->getAttributeLength( $node['Type'] );

		$sqlAttribute->Length = $length;

		if( $node['Null'] == 'NO' )
			$flags[] = SQLAttribute::NotNull;
		if( $node['Key'] == 'PRI')
			$flags[] = SQLAttribute::PrimaryKey;
		if( $node['Extra'] == 'auto_increment' )
			$flags[] = SQLAttribute::AutoIncrement;

		if( is_array( $flags ))
			$sqlAttribute->Flags = $flags;

		if( $node['Default'] == 'NULL' )
			$sqlAttribute->Default = null;
		else
			$sqlAttribute->Default = $node['Default'];

		return $sqlAttribute;
	}

	protected function getAttributeType( $input )
	{
		$end = strpos( $input, '(' );
		if( $end > 0 )
			$input = substr( $input, 0, $end );

		return $input;
	}

	protected function getAttributeLength( $input )
	{
		$begin = strpos( $input, '(' );
		if( $begin > 0 )
		{
			$begin++;
			$end = strpos( $input, ')' );
			$lenlen = $end - $begin;
			$length = substr( $input, $begin, $lenlen );
		}
		else
			$length = null;

		return (int)$length;
	}
}