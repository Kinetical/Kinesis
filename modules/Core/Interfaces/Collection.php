<?php 
namespace Core\Interfaces;

interface Collection extends \ArrayAccess, \IteratorAggregate, Object
{
	///function __construct( array $data, $strong = false );
	//function setData( array $data );
	
	function toArray();

	//function offsetSet( $key, IObject $value ); //ensure $value->getType() matches $this->getType() unless $this->getType()->isMixed()
}