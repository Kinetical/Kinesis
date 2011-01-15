<?php
namespace DBAL\Native;

abstract class Query extends \Core\Object
{
	const SQL = 'SQL';
	const XML = 'XML';
	const FILE = 'file';
	const DIR = 'directory';
	const INI = 'ini';
	const CSV = 'csv';

	private $_queryType = self::SQL;
	private $_dataType;
	private $_results;
	private $_text;
        private $_name;

	function __construct( $params = null )
	{
            if( !is_null( $params ) )
		$this->setFlags( $params );

            parent::__construct();
	}

	function setFlags( $params )
	{
            if( !is_array($params) )
                    $params = array( $params );

            foreach( $params as $param )
            {
                if($param instanceof \DBAL\Data\Model )
                {
                    $param = $param->getName();
                }
                if( $param == self::SQL
                    || $param == self::XML )
                {
                    $this->setQueryType( $param );
                }
                elseif( is_string( $param ) )
                {
                    $this->setDataType( $param );
                }
            }
	}
	function getDataType()
	{
		return $this->_dataType;
	}

	function setDataType( $type )
	{
                $this->_dataType = $type;
	}

	abstract function execute();

	function __toString()
	{
		return $this->_text;
	}

	function getQueryType()
	{
		return $this->_queryType;
	}

	function setQueryType( $type )
	{
		$this->_queryType = $type;
	}

	function setResults( $data )
	{
		return $this->_results = $data;
	}

	function hasResult()
	{
		return isset( $this->_results );
	}

	function getResults()
	{
		return $this->_results;
	}

	function getText()
	{
		return $this->_text;
	}

	function setText( $text )
	{
		if( !is_string( $text ))
			throw new \InvalidArgumentException('Query text must be string');

		$this->_text = $text;
	}

	abstract static function build();
	abstract static function create();

}