<?php
namespace DBAL\SQL\Data\Tree;

class Adapter extends \DBAL\SQL\Data\Adapter
{

	function __construct( $resource )
	{
		parent::__construct( $resource );
	}

        function initialize()
        {
            parent::initialize();
            if( !$this->getResource()->hasBehavior( \ORM\Entity\EntityObject::NestedSet ))
                 throw new \InvalidArgumentException('Tree Adapter only compatible with Nested Set entity');
        }

	function Fill( \DBAL\DataSource\SQLDataSource $dataSource )
	{
            $roots = $this->getRoots( $dataSource );
            $dataSource->clear();
            if( count( $roots ) > 0 )
            {
                foreach( $roots as $root )
                {
                    $this->setCommand( \ORM\Query::build( $this->getResource(),
                                                              \ORM\Query::HYDRATE_NESTEDSET )
                                                    ->select()
                                                    ->from( $this->getResource() )
                                                    ->where( 'lft', $root->lft, '>=' )
                                                    ->where( 'rgt', $root->rgt, '<=') );

                    //echo (string)$this->SelectCommand;
                    parent::Fill( $dataSource );
                }
            }

            return $dataSource->Data;
	}

	function Update( \DBAL\DataSource\SQLDataSource $dataSource )
	{
		$roots = $this->getRoots( $dataSource );

		foreach( $roots as $root )
		{
			$src = new \DBAL\DataSource\SQLDataSource( array( $root ) );
			$it = new \DBAL\Iterator\DataNodeIteratorIterator( new \DBAL\Iterator\DataNodeIterator( $root ) );

			foreach( $it as $control )
				$src[] = $control;

			parent::Update( $src );
		}
	}

	private function getRoots( \DBAL\DataSource\SQLDataSource $dataSource )
	{
		$roots = array();
		foreach( $dataSource as $dataItem )
		{
			if( $dataItem instanceof \Core\Object )
			{
				$root = $this->getRoot( $dataItem );
				$roots[ $root->Oid ] = $root;
			}
		}

		return $roots;
	}

	private function getRoot( $node )
	{
		if( $node !== null	&& $node->hasParent() )
			return $this->getRoot( $node->getParent() );

		return $node;
	}
}