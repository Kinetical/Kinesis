<?php
namespace DBAL\Query;

final class Transaction extends \Core\Object
{
	private $_queries;
	private $_prepared = false;



	function __construct()
	{
		$this->_queries = array();
                parent::__construct();
	}

	function isPrepared()
	{
		return $this->_prepared;
	}

	function prepare()
	{
		$this->_prepared = true;
	}

	protected function unprepare()
	{
		$this->_prepared = false;
	}

	function addQuery( $query )
	{

		$this->_queries[ $query->Oid ] = $query;
		if( !$this->isPrepared() )
			$this->prepare();
	}

	function removeQuery( $query )
	{

		if( array_key_exists( $query->Oid, $this->_queries ))
			unset( $this->_queries[ $query->Oid ]);
		if( count( $this->_queries ) < 1 )
			$this->unprepare();
	}


	function execute( $connection = null )
	{
		$results = array();
		foreach( $this->_queries as $query )
			$results[ $query->Oid ] = $query->execute( $connection );

		return $results;
	}
}