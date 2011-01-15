<?php
namespace DBAL\Data\Tree\Node;

class IteratorIterator extends \RecursiveIteratorIterator
{
	function __construct( $iterator, $mode = \RecursiveIteratorIterator::SELF_FIRST, array $flags = null )
	{
		if( method_exists($iterator,'getIterator'))
                    $iterator = $iterator->getIterator();
		if( !($iterator instanceof Iterator ))
			throw new \Exception('Iterator must be instance of Data\Tree\Node\Iterator, '.get_class( $iterator ).' provided.');
		parent::__construct( $iterator, $mode, $flags );
	}
}