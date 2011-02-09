<?php
namespace DBAL\SQL\View;

class Table extends \DBAL\SQL\View
{
    function getDefaultSelect()
    {
        $command = $this->getDefaultQuery();

        $command->build()
                ->tables();

        return $command;
    }

     function prepare( $source = null )
    {
        if( $source instanceof \DBAL\Data\Source )
        {
            if( $this->adapter->isRead() )
            {
                $source->Map->register( new \DBAL\Data\Filter\Scalar() );
                $source->Map->register( new \DBAL\Data\Filter\Table() );

            }
        }

        return parent::prepare( $source );
    }
}