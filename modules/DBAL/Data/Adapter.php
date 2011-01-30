<?php
namespace DBAL\Data;

class Adapter extends \Core\Object
{
    const SELECT = 1;
    const INSERT = 2;
    const UPDATE = 3;
    const DELETE = 4;
    
    protected $commands;

    private $_view;

    private function preserveKey( \Core\Object $dataItem )
    {

            $core = \Core::getInstance();
            $dataSet = $core->getDatabase()->getDataSet();

            if( $dataSet->hasModel( $schematicName = $dataItem->Type->name ) )
            {
                    $entity = $dataSet->Models[$schematicName];
                    // PERSIST ENTITY ON NEW OBJECT
                    $dataItem->Type->persist( $entity );

                    // PRESERVE KEYS
                    $primaryKey = $entity->getPrimaryKey()->getInnerName();

                    $entity->Index++;
                    $dataItem->{$primaryKey} = $entity->Index;
            }

            return $dataItem;
    }

    function getView( Source $dataSource = null )
    {
        if( !$this->hasView() )
            if( $dataSource instanceof Source )
                $this->_view = $dataSource->getView();
            else
                $this->_view = new View( $this );

        return $this->_view;
    }

    function setView( View $view )
    {
        if( $view->getAdapter() !== $this )
            $view->setAdapter( $this );

        return $this->_view = $view;
    }

    function hasView()
    {
        return !is_null($this->_view);
    }

    protected function execute( $type, Source $dataSource  )
    {
        $view = $this->getView( $dataSource );

        if( !$view->hasCommand() )
            $view->setCommand( $this->getCommand( $type ) );

        if( !$view->prepared() )
            $view->prepare( $dataSource );

        $dataSource( $view );

        $this->commands = array();
    }

    function Fill( Source $dataSource )
    {
        $this->execute( self::SELECT, $dataSource );
    }

    function Insert( Source $dataSource )
    {
        $this->execute( self::INSERT, $dataSource );
    }

    function Update( Source $dataSource )
    {
       $this->execute( self::UPDATE, $dataSource );
    }

    function Delete( Source $dataSource )
    {
        $this->execute( self::DELETE, $dataSource );
    }

    function getSelectCommand()
    {
        return $this->getCommand( self::SELECT );
    }
    function getInsertCommand()
    {
        return $this->getCommand( self::INSERT );
    }
    function getUpdateCommand()
    {
        return $this->getCommand( self::UPDATE );
    }
    function getDeleteCommand()
    {
        return $this->getCommand( self::DELETE );
    }

    function setSelectCommand( $command )
    {
        $this->setCommand( self::SELECT, $command );
    }
    function setInsertCommand( $command )
    {
        $this->setCommand( self::INSERT, $command );
    }
    function setUpdateCommand( $command )
    {
        $this->setCommand( self::UPDATE, $command );
    }
    function setDeleteCommand( $command )
    {
        $this->setCommand( self::DELETE, $command );
    }

    function isSelectCommand()
    {
        return $this->hasCommand( self::SELECT );
    }
    function isInsertCommand()
    {
        return $this->hasCommand( self::INSERT );
    }
    function isUpdateCommand()
    {
        return $this->hasCommand( self::UPDATE );
    }
    function isDeleteCommand()
    {
        return $this->hasCommand( self::DELETE );
    }

    function isWrite()
    {
        if( $this->isUpdateCommand()
            || $this->isInsertCommand()
            || $this->isDeleteCommand() )
            return true;

        return false;
    }

    function isRead()
    {
        return $this->isSelectCommand();
    }
    
    protected function hasCommand( $type )
    {
        return array_key_exists( $type, $this->commands );
    }

    protected function getCommand( $type )
    {
        if( !$this->hasCommand( $type )
             && $this->hasView() )
        {
            $view = $this->getView();
            switch( $type )
            {
                case self::SELECT:
                    $command = $view->getDefaultSelect();
                break;
                case self::INSERT:
                    $command = $view->getDefaultInsert();
                break;
                case self::UPDATE:
                    $command = $view->getDefaultUpdate();
                break;
                case self::DELETE:
                    $command = $view->getDefaultDelete();
                break;
            }

            $this->setCommand( $type, $command );
        }

        return $this->commands[$type];
    }

    protected function setCommand( $type, $command )
    {
        $this->commands[$type] = $command;
    }
}