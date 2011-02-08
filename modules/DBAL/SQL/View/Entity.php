<?php
namespace DBAL\SQL\View;

class Entity extends \DBAL\SQL\View
{
    function getDefaultSelect()
    {
        $command = $this->getDefaultQuery();

        $command->build()
                ->tables();

        return $command;
    }

    function getDefaultInsert()
    {

    }

    function getDefaultUpdate()
    {
        return $this->getDefaultQuery();
    }

    function getDefaultDelete()
    {

    }

    function prepare( $source = null )
    {
        if( $source instanceof \DBAL\Data\Source )
        {
            if( $this->adapter->isRead() )
            {
                $source->Map->register( new \DBAL\Data\Filter\Table() );
            }
            elseif( $this->adapter->isWrite() )
            {
                $source->Map->register( new \DBAL\SQL\Filter\Entity() );
            }
        }
        
        return parent::prepare();
    }
}