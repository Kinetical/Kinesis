<?php
namespace DBAL\SQL\Control;

class View extends \DBAL\Data\View
{
    function prepare()
    {
        $command = $this->getCommand();
        $resource = $command->getResource();

        if(   $command->isUpdateCommand()
           || $command->isInsertCommand() )
        {
            $resource->clearMappers();
            $resource->addMapper( new \DBAL\SQL\Tree\Mapper() );
            $resource->addMapper( new \DBAL\SQL\Control\Mapper( $this->getAdapter()->getSchematic() ) );
        }
        else
        {
            $resource->addMapper( new \Web\UI\Control\Mapper() );
        }
    }
}