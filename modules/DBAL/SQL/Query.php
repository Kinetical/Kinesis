<?php
namespace DBAL\SQL;

class Query extends \DBAL\Query
{
    private $_text;

    function __construct( $text = null, $params = null )
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

        $results = $this->getResults();

        $results->Data = $connection->getPlatform()->query( $this->getText() );

        return $results;
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
}
