<?php
namespace DBAL\SQL;

class Query extends \DBAL\Query
{
    private $_text;

    function __construct( $text = null, $params = array() )
    {
        

        if( !is_null( $text ))
            $this->setText( $text );

        parent::__construct( $params );
    }

    function initialize()
    {

        parent::initialize();
        $this->setFormat( self::SQL );
    }

    function execute( $connection = null )
    {
        if( ($connection = $this->resolve( $connection ) ) == false )
             return null;

        $this->results->clear();

        $result = $connection->getDatabase()->query( $this->getText() );


        return $this->results->toArray();
//            $results = $query->setResults( $stream->getMode()->execute( (string)$query  ) );
//
//
//            if( !is_bool( $query->getResults() ))
//            {
//                $results = $query->setResults( parent::flush());
//                //var_dump( $this->Query->Results );
//                $items = $this->getLoader()->flush();
//                return $items;
//            }
//            else
//                return null;
    }

    function getText()
    {
        return $this->_text;
    }

    function setText( $text )
    {
        if( !is_string( $text ))
                throw new \InvalidArgumentException('Query text must be string, '.get_class( $text ).' provided.');

        $this->_text = $text;
    }


    function __toString()
    {
        if( $this->_text == null
            && $this->builder instanceof \DBAL\Query\Builder )
            $this->_text = (string)$this->builder;

        return $this->_text;
    }
}
