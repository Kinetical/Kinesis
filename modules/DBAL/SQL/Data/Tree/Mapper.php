<?php
namespace DBAL\SQL\Data\Tree;

class Mapper extends \Core\Component\Mapper
{
	private $_lft = 0;
	private $_rgt;


	function __construct()
	{

	}

	function map( Object $object )
	{

		if( !$object->hasParent() )
			$this->_lft = 0;


		$this->_lft++;
		$object->lft = $this->_lft;


		$children = $object->Children;
		if( count( $children ) > 0 )
		{
			foreach( $children as $child )
				$this->map( $child );

			$this->_lft++;
			$object->rgt = $this->_lft;

		}
		else
		{
			$this->_lft++;
			$this->_rgt = $this->_lft;


			$object->rgt = $this->_rgt;
		}

		$this->cache( $object );

		return $object;
	}
}