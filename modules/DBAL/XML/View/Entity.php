<?php
namespace DBAL\XML\View;

class Entity extends \DBAL\XML\View
{
    function initialize()
    {
        parent::initialize();

        $this->setParameters( array( 'path' => 'site\entity.xml'));
    }
    function prepare( $dataSource = null )
    {
        parent::prepare();

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