<?php
namespace DBAL\Query\Model;

class Node extends \DBAL\Query\Node
{
	private $_model;

	function getModel()
	{
		return $this->_model;
	}

	protected function setModel( \DBAL\Data\Model $model )
	{
		$this->_model = $model;
	}

	function create( $data )
	{
		if( $data instanceof \DBAL\Data\Model )
                    $this->setModel( $data );

		if( is_array( $data ))
                    $data = $data[0];

		if( is_string( $data ) )
                    $this['table'] = $data;

		//if( is_string( $entity ))
			//$entity = \ORM\Entity\EntityManager::getInstance()->getEntity( $entity, $this->Query->Type );			

		return parent::create();
	}
}