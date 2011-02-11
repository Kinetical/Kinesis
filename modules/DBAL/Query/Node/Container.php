<?php
namespace DBAL\Query\Node;

class Container extends \DBAL\Query\Node
{
	private $_owner;

	function getOwner()
	{
            return $this->_owner;
	}

	protected function setOwner( $owner )
	{
            $this->_owner = $owner;
	}

	function create( $data )
	{
            if( $data instanceof \Core\Object )
                $this->setOwner( $data );

            if( is_array( $data ))
                $data = $data[0];

            if( is_string( $data ) )
                $this['owner'] = $data;

            //if( is_string( $entity ))
                    //$entity = \ORM\Entity\EntityManager::getInstance()->getEntity( $entity, $this->Query->Type );

            return parent::create();
	}
}