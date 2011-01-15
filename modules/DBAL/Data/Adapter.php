<?php
namespace DBAL\Data;

class Adapter extends \Core\Object
{
    protected $_selectCommand;
    protected $_insertCommand;
    protected $_deleteCommand;
    protected $_updateCommand;

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

    private function setSource( $data )
    {
        $this->getLoader()->setSource( $data );
    }

    private function getLoader()
    {
        return $this->_command->getQuery()->getResource()->getLoader();
    }

    private function preMap( $dataSource = null )
    {
            //$this->setSource( $dataSource );

            //return $this->getLoader()->flush( ClassMapper::Premapping );
    }

    function getView()
    {
            if( $this->_view == null )
                    $this->setView( new View( $this ));
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
            return ($this->_view !== null) ? true : false;
    }

    function Fill( Source $dataSource )
    {
            if( !$this->hasView() )
                $view = $this->setView( $dataSource->getView() );
            else
                $view = $this->getView();

            if( !$view->hasCommand() )
                $view->setCommand( $this->getSelectCommand() );
            //if( $this->isMapped() )
                    //$this->Mapper->Resource = $view->Command->Query->Resource;
            $dataSource->Fill( $view );
    }

    function Update( Source $dataSource )
    {
            if( !$this->hasView() )
                    $this->View = $dataSource->View;

            $this->View->Command->Resource->addMapper( new SqlObjectMapper( $this->Resource ) );

            foreach( $dataSource as $object )
            {
                    //$data = $this->Command->Resource->Hydrator->bindAll( ClassMapper::Premapping );
                    if( $object instanceof Object )
                    {
                            if( $object->Type->isPersisted() )
                            {
                                    $this->Command = $this->UpdateCommand;

                                    $this->preMap( $dataSource );
                                    $this->View->Command->set( $object->Data );
                                    $this->View->Command->where( $this->Resource->PrimaryKey->InnerName,
                                                                 $object->{$this->Resource->PrimaryKey->OuterName} );
                                    $dataSource->Fill( $this->View );
                            }
                            else
                            {
                                    if( $insertCommand == null )
                                            $insertCommand = $this->InsertCommand;

                                    $object = $this->preserveKey( $object );

                                    $this->preMap( $dataSource );
                                    $insertCommand->set( $object->Data );
                            }
                    }
            }

            if( $insertCommand !== null )
            {
                    $this->getView()->setCommand( $insertCommand );
                    $dataSource->Fill( $this->View );
            }


    }

    //protect

//	function getStream()
//	{
//		return $this->_command->getQuery()->getResource()->getStream();
//	}

    /*function setMapping( array $mapping, $bindingProperty = null )
    {
            $this->_classMapper = new ClassMapper(  $mapping, $bindingProperty );

    }

    function getMapper()
    {
            return $this->_classMapper;
    }

    function setMapper( ClassMapper $mapper )
    {
            return $this->_classMapper = $mapper;
    }

    function isMapped()
    {
            return ($this->_classMapper !== null )? true : false;
    }*/

    function getSelectCommand()
    {
            return $this->_selectCommand;
    }
    function getInsertCommand()
    {
            return $this->_insertCommand;
    }
    function getUpdateCommand()
    {
            return $this->_updateCommand;
    }
    function getDeleteCommand()
    {
            return $this->_deleteCommand;
    }

    function setSelectCommand( $command )
    {
            $this->_selectCommand = $command;
            $this->setCommand( $command );
    }
    function setInsertCommand( $command )
    {
            $this->_insertCommand = $command;
            $this->setCommand( $command );
    }
    function setUpdateCommand( $command )
    {
            $this->_updateCommand = $command;
            $this->setCommand( $command );
    }
    function setDeleteCommand( $command )
    {
            $this->_deleteCommand = $command;
            $this->setCommand( $command );
    }

    function isSelectCommand()
    {
        return !is_null( $this->_selectCommand );
    }
    function isInsertCommand()
    {
        return !is_null( $this->_insertCommand );
    }
    function isUpdateCommand()
    {
        return !is_null( $this->_updateCommand );
    }
    function isDeleteCommand()
    {
        return !is_null( $this->_deleteCommand );
    }
    protected function setCommand( $command )
    {
        if( $this->hasView() )
        {
            $view = $this->getView();

            if( !$view->isPrepared() )
            {
                $view->setCommand( $command );
                $view->prepare();
            }
        }

        return $command;
    }
}