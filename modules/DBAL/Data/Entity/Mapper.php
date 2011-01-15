<?php
namespace DBAL\Data\Entity;

class Mapper extends \DBAL\Data\Tree\Node\Mapper
{
	function __construct( $mapping = null )
	{
		if( !is_array( $mapping ))
                    $mapping = array();

                parent::__construct( $mapping );

		$this->StateChanged->attach( $this, 'OnStateChanged' );
	}

        function initialize()
        {
            if(!$this->hasMapping() )
                $this->setMapping( array('entity'         => '\ORM\Entity\EntityObject',
                                         'entity.name'    => 'InnerName',
                                         'attribute'      => '\ORM\Entity\EntityAttribute',
                                         'attribute.name' => 'InnerName',
                                         'attribute.type' => 'DataType') );
            parent::initialize();
        }

	function map( \Web\UI\DocumentElement $node )
	{
		$mappedObject = parent::map( $node, true );

		if( $node->hasChildren() )
                    foreach( $node->getChildren() as $child )
                        if( $this->has( $child->Oid ) )
                        {
                            $mapped = $this->get( $child->Oid );

                            if( $mapped instanceof \ORM\Entity\EntityAttribute )
                                $mappedObject->addAttribute( $mapped );
                            elseif( $mapped instanceof \ORM\Entity\EntityRelationship )
                            {
                                $mapped->setAssociation( $this->get( $node->Oid ));
                                $mappedObject->addRelation( $mapped );
                            }

                        }

		if( $mappedObject instanceof \ORM\Entity\EntityObject )
		{
                    $attrClass = $this->getBindingClass('attribute');
                    
                    if( $mappedObject->getPrimaryKey() == null )
                        $mappedObject->addAttribute( new $attrClass( 'id',
                                                     'integer',
                                                    \ORM\Entity\EntityAttribute::PrimaryKey,
                                                    \ORM\Entity\EntityAttribute::AutoIncrement,
                                                    \ORM\Entity\EntityAttribute::NotNull ));
                    if( is_string( $mappedBehaviors = $mappedObject->getBehaviors() ))
                    {
                        $behaviors = array();
                        $behavior_names = explode(' ', $mappedBehaviors );
                        if( is_array( $behavior_names ))
                            foreach( $behavior_names as $name )
                                if( strtolower( $name ) == 'slug' )
                                    $behaviors[] = \ORM\Entity\EntityObject::Slug;
                                elseif( strtolower( $name ) == 'timestamp' )
                                    $behaviors[] = \ORM\Entity\EntityObject::TimeStamp;
                                elseif( strtolower( $name ) == 'nestedset' )
                                    $behaviors[] = \ORM\Entity\EntityObject::NestedSet;

                        $mappedObject->setBehaviors($behaviors);
                        
                    }

                    if( $mappedObject->hasBehavior( \ORM\Entity\EntityObject::TimeStamp ) )
                    {
                        $created = new $attrClass('created_at', 'timestamp' );
                        $updated = new $attrClass('updated_at', 'timestamp' );

                        $created->setDefault( '0000-00-00 00:00:00' );
                        $updated->setDefault( 'CURRENT_TIMESTAMP' );

                        $created->addFlag( \ORM\Entity\EntityAttribute::NotNull );
                        $updated->addFlag( \ORM\Entity\EntityAttribute::NotNull );

                        $mappedObject->addAttribute($created);
                        $mappedObject->addAttribute($updated);
                    }

                    if( $mappedObject->hasBehavior( \ORM\Entity\EntityObject::NestedSet ) )
                    {
                        $mappedObject->addAttribute( new $attrClass('lft', 'integer' ));
                        $mappedObject->addAttribute( new $attrClass('rgt', 'integer' ));
                    }

                    //$entityManager = \Core\Manager\EntityManager::getInstance();
                    //$entityManager->addEntity( $mappedObject );

                    $relationMapper = new SQLRelationshipMapper();
                    $mappedObject = $relationMapper->map( $mappedObject );
		}

		return $mappedObject;
	}

	function OnStateChanged( $newState )
	{
		if( $this->getState() == ClassMapper::Completed )
		{
			//$all = $this->getMapped();
			$mapped = $this->getMappedByType('EntityAttribute','SQLAttribute');
                        $dataSet = \Core::getInstance()->getDatabase()->getDataSet();

			foreach( $mapped as $object )
			{
				if( $object instanceof \ORM\Entity\EntityAttribute
					&& ($dataType = $object->getDataType()) instanceof \ORM\Type\EntityType )
				{
					$relation = new \ORM\Entity\Relationship\OneToOneRelationship();
					$relation->setName($object->getOuterName());
					$relation->setEntity( $object->getEntity());
					$relation-setAssociation($dataSet->getSchematic( $dataType->getEntity() ));
					$object->getEntity()->addRelation( $relation );
				}
			}
		}
	}
}