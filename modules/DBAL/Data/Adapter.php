<?php
namespace DBAL\Data;

class Adapter extends \Core\Object
{
    const SELECT = 1;
    const INSERT = 2;
    const UPDATE = 3;
    const DELETE = 4;
    
    protected $commands;
    protected $view;

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

    function getView()
    {
        return $this->view;
    }

    function setView( View $view )
    {
        $view->setAdapter( $this );

        return $this->view = $view;
    }

    function hasView()
    {
        return !is_null($this->view);
    }

    protected function execute( &$dataSource = null, $type = null  )
    {
         if( !$this->hasView() )
             if( $dataSource instanceof Source )
                $view = $dataSource->getDefaultView();
             else
                 throw new \DBAL\Exception('Adapter('.get_class( $this ).') failed to locate a valid view');
         else
             $view = $this->view;

        if( !$view->hasCommand() )
            if( !is_null( $type ))
            {
                $view->setCommand( $this->getCommand( $type ) );
            }
            elseif( $this->hasCommand() )
            {
                $view->setCommand( $this->getCommand() );
            }
            else
                throw new \DBAL\Exception('Adapter('.get_class( $this ).') failed to locate a valid executable');

        return $view( $dataSource );
    }

    function __invoke( &$dataSource = null )
    {
        return $this->execute( $dataSource );
    }

    function Fill( &$dataSource )
    {
        $this->execute( $dataSource, self::SELECT );
    }

    function Insert( &$dataSource )
    {
        $this->execute( $dataSource, self::INSERT );
    }

    function Update( &$dataSource )
    {
       $this->execute( $dataSource, self::UPDATE );
    }

    function Delete( &$dataSource )
    {
        $this->execute( $dataSource, self::DELETE );
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
        $this->setCommand( $command, self::SELECT );
    }
    function setInsertCommand( $command )
    {
        $this->setCommand( $command, self::INSERT );
    }
    function setUpdateCommand( $command )
    {
        $this->setCommand( $command, self::UPDATE );
    }
    function setDeleteCommand( $command )
    {
        $this->setCommand( $command, self::DELETE );
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
        if( $this->commands instanceof \DBAL\Query )
            return $this->commands->isWrite();
        elseif( is_array( $this->commands ) )
            return $this->isUpdateCommand() ||
                   $this->isInsertCommand() ||
                   $this->isDeleteCommand();

        return false;
    }

    function isRead()
    {
        if( $this->commands instanceof \DBAL\Query )
            return $this->commands->isWrite();
        elseif( is_array( $this->commands ) )
            return $this->isSelectCommand ();

        return false;
    }
    
    protected function hasCommand( $type = null )
    {
        if( is_null( $type ))
            return $this->commands instanceof \Util\Interfaces\Executable;
        elseif( is_array( $this->commands ))
            return array_key_exists( $type, $this->commands );

        return false;
    }

    protected function getCommand( $type = null )
    {
        if( is_null( $type ))
            return $this->commands;
        
        if( !$this->hasCommand( $type ) &&
             $this->hasView() )
        {
            switch( $type )
            {
                case self::SELECT:
                    $command = $this->view->getDefaultSelect();
                break;
                case self::INSERT:
                    $command = $this->view->getDefaultInsert();
                break;
                case self::UPDATE:
                    $command = $this->view->getDefaultUpdate();
                break;
                case self::DELETE:
                    $command = $this->view->getDefaultDelete();
                break;
            }

            $this->setCommand( $command, $type );
        }

        return $this->commands[$type];
    }

    protected function setCommand( $command, $type = null )
    {
        if( is_null( $type ))
            $this->commands = $command;
        else
        {
            if( !is_array( $this->commands ))
                $this->clear();

            $this->commands[$type] = $command;
        }
        
        $command->setParameters(
                array('StreamMode' => $this->isRead()
                                      ? \IO\Stream\Mode::Read
                                      : \IO\Stream\Mode::Write )
                );
    }

    function clear()
    {
        $this->commands = array();
    }
}