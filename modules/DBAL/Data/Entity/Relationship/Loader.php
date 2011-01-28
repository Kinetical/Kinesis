<?php
namespace DBAL\Data\Entity\Relationship;

abstract class Loader extends \DBAL\Data\Loader
{


	function __construct( \Core\Object $object )
	{
		$this->setSource( $object );
	}

	function load( $path, $args = null)
	{

	}
}