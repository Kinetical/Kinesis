<?php
namespace DBAL\SQL\Data;

class Source extends \DBAL\Data\Source
{
	private $_database;

	function __construct( array $data = null )
	{
            parent::__construct( $data );
            unset( $this->DataType);
        }

        function initialize()
        {
            parent::initialize();
            $this->Database = \Core::getInstance()->getDatabase();
        }

	function getDatabase()
	{
		return $this->_database;
	}

	function setDatabase( \DBAL\Database\SQLDatabase $database )
	{
		$this->_database = $database;
	}

	function getView()
	{
		return parent::getView();
		//return new SqlDataView();
	}

	function Fill(\DBAL\DataView $view )
	{
            $view->getCommand()->getQuery()->getResource()->getStream()->setConnection( $this->getDatabase()->getConnection() );
			/*
		if(!$view->Command->Query->Resource->Stream->isOpen() )
			$view->Command->Query->Resource->Stream->open();*/

		//$this->Database->Select();

		return parent::Fill( $view );
	}
}