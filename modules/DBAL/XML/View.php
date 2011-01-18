<?php
namespace DBAL\XML;

class View extends \DBAL\Data\View
{
    private $_filePath;
    private $_xpath;

    function __construct( $filePath, $xpath = null, \DBAL\Data\Adapter $adapter = null )
    {
        $this->_filePath = $filePath;
        $this->_xpath = $xpath;

        parent::__construct( $adapter );
    }

    function getDefaultQuery()
    {
        return new \DBAL\XML\Query();
    }

    function getDefaultSelect()
    {
        $command = $this->getDefaultQuery();
        
        $command->build()
                ->from( $this->_filePath );

        new Filter\Node( $command );

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
                       ->update( $this->_filePath );
    }

    function getDefaultDelete()
    {
        return $this->getDefaultUpdate();
    }

    function prepare( \DBAL\Data\Source $dataSource = null )
    {
        if( $this->adapter->isSelectCommand()
            && is_string( $this->_xpath ))
        {
            $this->command->build()
                          ->where( $this->_xpath );
        }
        elseif( $this->adapter->isUpdateCommand()
             || $this->adapter->isDeleteCommand()
             || $this->adapter->isInsertCommand() )
        {
            if( $dataSource instanceof \DBAL\XML\Document )
                $root = $dataSource->getRoot();
            else
                $root = $dataSource->Data[0];

            if( $root instanceof \DBAL\Data\Tree\Node )
                $this->command->build()
                              ->set( $root );
        }
    }
}