<?php
namespace DBAL\Data\Entity\Object;

class Mapper extends \Core\Component\Mapper
{
	private $_entity;

	function __construct( \ORM\Entity\EntityObject $entity )
	{
		$this->_entity = $entity;
		$mapping = array( 'dataitem' => $entity->InnerName );

                foreach( $this->_entity->Attributes as $attribute )
                {
                        //TODO: IF OUTERNAME EXISTS, MAP TO THAT INSTEAD
                        $mapping['dataitem.'.$attribute->InnerName] = $attribute->InnerName;

                }
		
		parent::__construct( $mapping, null,'Data');

	}

	function getEntity()
	{
		return $this->_entity;
	}

	function map( \Core\Object $item )
	{




		return parent::map( $item );
	}
}