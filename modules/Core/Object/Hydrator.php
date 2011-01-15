<?php
namespace Core\Object;

class Hydrator extends \DBAL\Hydrator
{

	function __construct( \IO\Resource $resource, $type = 'Core\Object' )
	{
		$this->setDataType( $type );
		parent::__construct( $resource );
	}


}