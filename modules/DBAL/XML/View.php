<?php
namespace DBAL\XML;

use DBAL\Data;

class View extends \DBAL\Data\View
{
    function getDefaultQuery()
    {
        return new \DBAL\XML\Query();
    }

    function getDefaultSelect()
    {
        $command = $this->getDefaultQuery();

        $command->build()
                ->from( $this->Parameters['path'] );

        return $command;
    }

    function getDefaultInsert()
    {
        return $this->getDefaultUpdate();
    }

    function getDefaultUpdate()
    {
        $command = $this->getDefaultQuery();
        return $command->build()
                       ->update( $this->Parameters['path'] );
    }

    function getDefaultDelete()
    {
        return $this->getDefaultUpdate();
    }

    function prepare( $source = null )
    {
        if( $source instanceof \DBAL\Data\Source )
        {
            if( $this->adapter->isRead() )
            {
                if( $this->Parameters->exists('xpath'))
                    $this->command->insertParameter('xpath', $this->Parameters['xpath']);
                    
                $source->Map->register( new \DBAL\XML\Filter\SimpleXML() );
                if( $this->command->hasParameter('xpath') )
                {
                    $source->Map->register( new \DBAL\XML\Filter\Xpath( $this->command->Parameters ) );
                }
                $source->Map->recurse( new Filter\Node() );
            }
            elseif( $this->adapter->isWrite() )
            {
                if( $source instanceof \DBAL\XML\Document )
                    $root = $source->getRoot();
                else
                    $root = $source->Data[0];

                if( $root instanceof \DBAL\Data\Tree\Node )
                    $this->command->build()
                                  ->set( $root );
            }
        }

        return parent::prepare( $source );
    }
}