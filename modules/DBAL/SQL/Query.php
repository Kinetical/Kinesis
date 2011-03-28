<?php
namespace DBAL\SQL;

class Query extends Transaction
{
    private $_text;
    
    function initialise()
    {
        $this->Parameters['Namespace'] = 'DBAL\SQL\Query';
    }

    function __construct( $text = null, array $params = array() )
    {
        if( !is_null( $text ))
            $this->setText( $text );

        parent::__construct( $params );
    }

    function execute()
    {
        $query = $this->getText();
        if( is_array( $query ))
        {
            $resource = array();
            foreach( $query as $qry )
            {
                echo 'fuck';
                var_dump( (string) $qry );
                $resource[] = $this->database->query( (string)$qry );
            }
        }
        else
        {
            $resource = $this->database->query( (string)$query );
        }
        
        $results = array();
        
        if( is_resource( $resource ))
        {
            $this->Parameters['StreamInput'] = $resource;
            foreach( $this as $result )
                $results[] = $result ;
        }

        return $results;
    }

    function getText()
    {
        if( is_null( $this->_text ))
            $this->_text = (string)$this;
        
        return $this->_text;
    }

    function setText( $text )
    {
        if( !is_string( $text ))  //TODO: ATTEMPT TO CONVERT TO STRING
            throw new \InvalidArgumentException('Query text must be string, '.get_class( $text ).' provided.');

        $this->_text = $text;
    }

    protected function assemble()
    {
        $this->_text = parent::assemble();
        
        return $this->_text;
    }

    function __toString()
    {
        if( is_null( $this->_text ) )
            $this->assemble();
        
        if( is_array( $this->_text ))
            $this->_text = implode(';', $this->_text );

        return $this->_text;
    }
}
