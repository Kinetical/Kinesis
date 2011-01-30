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
                ->from( $this->parameters['path'] );

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
                       ->update( $this->parameters['path'] );
    }

    function getDefaultDelete()
    {
        return $this->getDefaultUpdate();
    }

    function prepare( \DBAL\Data\Source $dataSource = null )
    {
        if( $this->adapter->isRead() )
        {
            if( $this->parameters->exists('xpath'))
                $this->command->build()
                              ->where( $this->parameters['xpath'] );

            $this->Filters->register( new \DBAL\Data\Filter\Recursive( new Filter\Node() ) );
        }
        elseif( $this->adapter->isWrite() )
        {
            if( $dataSource instanceof \DBAL\XML\Document )
                $root = $dataSource->getRoot();
            else
                $root = $dataSource->Data[0];

            if( $root instanceof \DBAL\Data\Tree\Node )
                $this->command->build()
                              ->set( $root );
        }

        return parent::prepare();
    }
}