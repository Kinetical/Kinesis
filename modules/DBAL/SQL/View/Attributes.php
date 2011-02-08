<?php
namespace DBAL\SQL\View;

class Attributes extends \DBAL\SQL\View
{
    function getDefaultSelect()
    {
        $command = $this->getDefaultQuery();

        $command->build()
                ->columns()
                ->from( $this->parameters['table'] );

        return $command;
    }

    function prepare( $source = null )
    {
        if( $source instanceof \DBAL\Data\Source )
        {
            if( $this->adapter->isRead() )
            {
                $source->Map->register( new \DBAL\Data\Filter\Column() );
            }
        }
    }
}