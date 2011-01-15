<?php
namespace DBAL\Data\Tree\Nested;

class Hydrator extends \Core\Object\Hydrator
{
	private $_keyCache = array();
	private $_cache = array();
	private $_root;


	function __construct( \Core\Resource $resource, $type = 'Core\Object')
	{
		parent::__construct( $resource, $type );
		$this->setCallback('setData');

	}

	function flush()
	{
		$results = parent::flush();

		$root = $this->load( null, $this->_cache[ $this->_root ] );
		if( $root !== null )
			return $root;

		return $results;
	}

	function &bindNode( $node, $parent = null )
	{
		$parents = $this->_keyCache;

                $keys = array_keys( $parents, (int)$node->Data['id'] );
		foreach( $keys as $id => $pid )
		{
                    $child = $this->_cache[ $pid ];
                    $children[] = $this->bindNode(  $child, $node );
		}
		if( is_array( $children ))
			$node->setChildren( $children );

		if( $parent !== null )
			$node->setParent( $parent );

		return $node;
	}

	protected function load( $path, $args = null )
	{

		$dataItem = parent::load( $path, $args );
                $entity = $this->getSchematic();
                
		$primaryKeyField = $entity->getPrimaryKey()->getInnerName();
                $primaryKey = $dataItem->{$primaryKeyField};

		$this->_keyCache[ $primaryKey ] = (int)$data['parent'];
		$this->_cache[ $primaryKey ] = $dataItem;
                $parent = $data['parent'];

		if( (int)$data['parent'] == 0 )
			$this->_root = $primaryKey;

		return $dataItem;
	}
}