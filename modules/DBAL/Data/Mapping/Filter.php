<?php
namespace DBAL\Data\Mapping;

class Filter extends \DBAL\Query\Filter
{
    protected $mapping;

    protected $matches = array();

    function __construct( $query, array $params = array(), array $mapping = array() )
    {
        parent::__construct( $query, $params );

        $this->setMapping( $mapping );
    }

    function initialize()
    {
        parent::initialize();

        $this->mapping = new \DBAL\Data\Mapping( array(), $this );
    }

    function setMapping( array $mapping )
    {
        $this->mapping->merge( $mapping );
    }

    function getMapping()
    {
        return $this->mapping;
    }

    function match( $subject )
    {
        if( array_key_exists( $subject, $this->matches ))
            return $this->matches[$subject];

        if( array_key_exists( $subject, $this->mapping->Data ))
            return $this->mapping[$subject];
        
        if( $this->mapping->count() > 0 )
            return $this->wildcard( $subject );

        return false;
    }

    protected function wildcard( $subject )
    {
        $keys = array_map('preg_quote', $this->mapping->keys() );

        $keys = str_replace( array('\*','\?'),
                              array('(.*?)','[0-9]'),
                              $keys );

        $keys = array_combine( $keys, $this->mapping->toArray() );

        foreach( $keys as $regex => $mapping )
            if( strpos($regex, '*') &&
                preg_match('/^'.$regex.'$/', $subject ) > 0 )
                return $this->matches[$subject] = $mapping;

        return false;
    }

    protected function execute( array $params = null )
    {
        $subject = $params['input'];
        if( is_array( $subject ) || 
            $subject instanceof \Traversable )
            foreach( $subject as $key => $item )
                $subject[ $key ] = $this->map( $item );
        else
            $subject = $this->map( $subject );

        return $subject;
    }

    protected function map( $subject )
    {
        if( $this->mapping->exists( $subject ))
            return $this->match( $subject );

        return $subject;
    }
}
