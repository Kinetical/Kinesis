<?php
namespace DBAL\SQL\Data;

class Adapter extends \DBAL\Data\Adapter
{

	function __construct( $resource = null )
	{
		parent::__construct( $resource );
	}

	function Fill(\DBAL\SQL\Data\Source $dataSource )
	{
		return parent::Fill( $dataSource );
	}

        function getSchematic()
        {
            return $this->getResource();
        }

        function setSchematic( \DBAL\SQL\Data\Entity $schematic )
        {
            $this->setResource( $schematic );
        }

        function setResource( $resource )
        {
            
            $core = \Core::getInstance();
            $dataSet = $core->getDatabase()->getDataSet();
            if( $resource instanceof \DBAL\Data\Table )
                $resource = $resource->getEntity();
            elseif( is_string( $resource )
                && $dataSet->hasModel($resource,'entity') )
                $resource = $dataSet->getModel($resource);
            if( ($resource instanceof \DBAL\Data\Model) )
                parent::setResource( $resource );
            else
                throw new \InvalidArgumentException ();
        }

	function Update( \DBAL\SQL\Data\Source $dataSource )
	{
		//TODO: collect views in transaction, run all update queries with single database call
		if( !$this->hasView() )
			$this->setView( $dataSource->getView() );

		parent::Update( $dataSource );
	}

	function getDeleteCommand() {
	}

	function getInsertCommand() {
		return \DBAL\Query::build( \DBAL\Query::SQL )
			   ->insert( $this->getResource() );
	}

	function getSelectCommand()
        {
            if( $this->_command == null )
                $this->setCommand( \DBAL\Query::build( \DBAL\Query::SQL )
                                             ->select()
                                             ->from( $this->getResource() ));

            return parent::getSelectCommand();
	}

	function getUpdateCommand() {
		return \DBAL\Query::build( \DBAL\Query::SQL )
			   ->update( $this->getResource() );
	}

        function setCommand( $command )
        {
            $command = parent::setCommand( $command );

            $command->getQuery()->getResource()->setModel( $this->getModel()  );
        }
}