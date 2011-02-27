<?php
namespace DBAL\XML\View;

class Entity extends \DBAL\XML\View
{
    function initialise()
    {
        parent::initialise();

        $this->setParameters( array( 'path' => 'site\entity.xml'));
    }
    function prepare( $source = null )
    {
        parent::prepare( $source );

        if( $source instanceof \DBAL\Data\Source )
        {
            if( $this->adapter->isRead() )
            {
                $source->Map->recurse( new \DBAL\Data\Filter\Entity() );
            }
        }
        
        
        return $this->command;
    }
}