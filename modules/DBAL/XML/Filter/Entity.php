<?php
namespace DBAL\XML\Filter;

use DBAL\Data;
use DBAL\XML\Data\Tree;

/**
 * XML Entity Filter
 * This class receives XML entity definition and creates a proper SQL entity
 */
class Entity extends \DBAL\Data\Filter\Entity
{
    /**
     * This method retrieves base model and appends needed metadata
     * in order for XML definition to make a valid SQL schematic
     * @param DBAL\Data\Entity $mappedObject SQL Entity object
     * @param DBAL\XML\Tree\Node $subject XML entity definition
     * @return Entity 
     */
    protected function map( $mappedObject, $subject )
    {
        // RETRIEVE MODEL ENTITY FROM PARENT FILTER
        $entity = parent::map( $mappedObject, $subject );
        
        if( $entity instanceof Data\Entity )
        {
            // CLASS OF ATTRIBUTE FROM MAPPING
            $attrClass = $this->getBindingClass('attribute');
            //attrClass = "DBAL\Data\Entity\Attribute"; // (CLASS NAME)
            
            // IF THE TABLE LACKS A PRIMARY KEY, ADD ONE
            if( is_null( $entity->getKey() ) )
            {
                $primaryKey = new $attrClass( 'id',
                                              'integer',
                                              Data\Entity\Attribute::PrimaryKey,
                                              Data\Entity\Attribute::AutoIncrement,
                                              Data\Entity\Attribute::NotNull );
                $entity->Attributes->add( $primaryKey );
            }
            
            // CREATE FROM BEHAVIOR DEFINITION A BEHAVIOR ARRAY
            if( is_string( $mappedBehaviors = $entity->Behaviors ))
            {
                $behaviors = array();
                $behavior_names = explode(' ', $mappedBehaviors );
                if( is_array( $behavior_names ))
                {
                    foreach( $behavior_names as $name )
                    {
                        $name = strtolower( $name );
                        switch( $name )
                        {
                            case 'slug':
                                $behavior = Data\Entity\Behavior::Slug;
                                break;
                            case 'timestamp':
                                $behavior = Data\Entity\Behavior::TimeStamp;
                                break;
                            case 'nestedset':
                                $behavior = Data\Entity\Behavior::NestedSet;
                                
                        }
                        
                        $behaviors[ $behavior ] = $behavior;
                    }
                }

                $entity->Behaviors = $behaviors;
            }

            // CREATE ATTRIBUTES TO TRACK RECORD USAGE
            if( $entity->hasBehavior( Data\Entity\Behavior::TimeStamp ) )
            {
                $created = new $attrClass('created_at', 'timestamp' );
                $updated = new $attrClass('updated_at', 'timestamp' );

                $created->setDefault( '0000-00-00 00:00:00' );
                $updated->setDefault( 'CURRENT_TIMESTAMP' );

                $created->addFlag( Data\Entity\Attribute::NotNull );
                $updated->addFlag( Data\Entity\Attribute::NotNull );

                $entity->Attributes->add($created);
                $entity->Attributes->add($updated);
            }

            // CREATE ATTRIBUTES TO MAINTAIN NESTED SET
            if( $entity->hasBehavior( Data\Entity\Behavior::NestedSet ) )
            {
                $entity->Attributes->add( new $attrClass('lft', 'integer' ));
                $entity->Attributes->add( new $attrClass('rgt', 'integer' ));
            }

            //TODO: CLOSURE LIST ATTRIBUTES
            //TODO: PATH ENUMERATOR ATTRIBUTES
            //TODO: MAP RELATIONSHIPS
            //$relationMapper = new SQLRelationshipMapper();
            //$entity = $relationMapper->map( $entity );
        }
                
        return $entity;
    }
}