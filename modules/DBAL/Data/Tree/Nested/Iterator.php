<?php
namespace \DBAL\Data\Tree\Nested;

class Iterator implements \RecursiveIterator
{
	private $_tree;
	private $_position = 0;


	function __construct( $tree )
	{
		$this->_tree = $tree;
	}

	public function current() {
		if( $this->_tree->Type->isPersisted()
			&& $this->_tree->Type->isPersistedBy('EntityObject')
			&& $this->_tree->Type->getPersistenceObject()->hasBehavior(\ORM\Entity\EntityObject::NestedSet) )
			return $this->_tree->children[ $this->_position ];
		if( is_array( $this->_tree ))
			return $this->_tree[ $this->_position ];
	}

	public function getChildren() {
		return new NestedSetIterator($this->current());


	}

	public function hasChildren() {
		if( $this->_tree->Type->isPersisted()
			&& $this->_tree->Type->isPersistedBy('EntityObject')
			&& $this->_tree->Type->getPersistenceObject()->hasBehavior(\ORM\Entity\EntityObject::NestedSet) )
			return $this->_tree->children[ $this->_position ]->hasChildren();
		if( is_array( $this->_tree ))
			return $this->_tree[ $this->_position ]->hasChildren();
	}

	public function key() {
		return $this->position;
	}

	public function next() {
		++$this->position;
	}

	public function rewind() {
		$this->position = 0;
	}

	public function valid($position = null) {
		if( $position == null )
			$position = $this->position;

		if( $this->_tree->Type->isPersisted()
			&& $this->_tree->Type->isPersistedBy('EntityObject')
			&& $this->_tree->Type->getPersistenceObject()->hasBehavior(\ORM\Entity\EntityObject::NestedSet) )
			return (count( $this->_tree->children ) > $position) ? true : false;
		if (is_array( $this->_tree ))
			return (count( $this->_tree ) > $position) ? true : false;
	}


}