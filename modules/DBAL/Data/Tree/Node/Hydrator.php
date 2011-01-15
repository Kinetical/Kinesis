<?php
namespace DBAL\Data\Tree\Node;

class Hydrator extends \Core\Object\Hydrator
{
	function __construct( \Core\Resource $resource, $type = 'DBAL\DataNode')
	{
		parent::__construct( $resource, $type );
		$this->setCallback('setAttributes');
	}

	function flush()
	{
            
		$this->_mapperStates = func_get_args();
		if( !is_array( $this->_mapperStates )
			||  count( $this->_mapperStates ) < 1 )
			$this->_mapperStates = array( ORM\Mapper\ClassMapper::Mapping );

		if( ($bindSource = $this->getSource()) instanceof \SimpleXMLElement )
			return $this->load( null, $bindSource );

		return parent::flush();
	}

	function load( $path, $args = null )
	{
            if( is_array( $args ))
            {
                $node = $args['node'];
                $parent = $args['parent'];
            }
            else
                $node = $args['node'];

            $dataNode = parent::load( $path, $this->getAttributes( $node) );
            if( $parent !== null )
                $dataNode->setParent( $parent );

            if( $node->count() > 0 )
                foreach( $this->children() as $xmlNode )
                    $this->load( $path.$xmlNode->getName(),
                                array('node'=>$xmlNode, 'parent'=>$dataNode) );

            return $dataNode;
	}

	protected function getAttributes( $node )
	{
		if( $node instanceof \SimpleXMLElement )
		{
			if( count($node->attributes()) > 0 )
			{
				$attr = (array)$node->attributes();
				$attr = $attr['@attributes'];
			}
		}
		elseif( $node instanceof \DBAL\DataItem )
		{
			// TODO: CONVERT NESTED SET RESULT INTO NODES
		}
		elseif( is_array( $node )
				&& count( $node ) > 0 )
				$attr = $node;

		if( !is_array( $attr ))
			$attr = array();

		return $attr;
	}
}