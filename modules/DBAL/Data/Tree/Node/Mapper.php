<?php
namespace DBAL\Data\Tree\Node;

class Mapper extends \Core\Component\Mapper
{
	function __construct( array $mapping )
	{
		parent::__construct($mapping,'name','Attributes');
	}
	function map( \DBAL\DataNode $node )
	{
		$mappedObject = parent::map( $node );
		///$mappedObject->Data = $node->Attributes;

		if(  $node->hasChildren() )
			foreach( $node->getChildren() as $child )
				if( $this->isMapped( $child ))
					$mappedObject->addChild( $this->map( $child ) );


		//var_dump($mappedObject);
		return $mappedObject;

	}
}