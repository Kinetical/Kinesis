<?php
namespace \DBAL\Data\Tree\Nested;

class IteratorIterator extends \RecursiveIteratorIterator
{
	function __construct( $iterator )
	{
		if( method_exists($iterator,'getIterator'))
			$iterator = $iterator->getIterator();
		if( !($iterator instanceof NestedSetIterator ))
			throw new Exception('Iterator must be instance of NestedSetIterator');
		parent::__construct( $iterator, \RecursiveIteratorIterator::SELF_FIRST );
	}
}