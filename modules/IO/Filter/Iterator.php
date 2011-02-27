<?php
namespace IO\Filter;

class Iterator extends \Util\Iterator
{
    protected $handler;

    protected $input;


    function __construct( $input = null )
    {
        parent::__construct();

        if( is_array( $input ))
            $this->setInput( $input );
    }

    function initialise()
    {
        //parent::initialize();

        $this->input = new \Util\Collection();
    }

    function input()
    {
        
        $input = $this->input[ $this->position() ];

        if( $this->isFiltered( \IO\Filter::INPUT ))
        {
            $params = array('input' => $input,
                            'state' => \IO\Filter::INPUT );

            return $this->execute( $params );
        }

        return $input;
    }

    function output( $input = null )
    {
        if( !$this->isFiltered( \IO\Filter::OUTPUT ))
             return $input;
        
        $params = array('input' => $input,
                        'state' => \IO\Filter::OUTPUT );

        return $this->execute( $params );
    }

    protected function execute( array $params = array() )
    {
        $handler = $this->handler;

        $output = $handler( $params );

        return $output;
    }

    function __invoke( array $params = array() )
    {
        return !is_null($this->execute( $params ));
    }

    protected function isFiltered( $state = \IO\Filter::OUTPUT )
    {
        if( $this->hasMap() )
            return true;

        return false;
    }

    public function current( $input = null )
    {
        if( is_null( $input ))
            $input = $this->input();

        $this->Data = $this->output( $input );

        return $this->Data;
    }

    function rewind()
    {
        $this->Data = null;
        return parent::rewind();
    }

    function valid()
    {
        if( $this->position < $this->input->count() )
            return true;

        return false;
    }

    function getInput()
    {
        return $this->input;
    }

    function setInput( $value )
    {
        if( is_array( $value ))
            $this->input->merge( $value );
        else
            $this->input->add( $value );
    }

    function getHandler()
    {
        return $this->handler;
    }

    function setHandler( \IO\Filter\Handler $handler )
    {
        $this->handler = $handler;
    }

    function setMap( $map )
    {
        if( is_null( $this->handler ))
            $this->handler = new \IO\Filter\Handler();

        $this->handler->setMap( $map );
    }

    function getMap()
    {
        if( !$this->hasMap() )
             return null;

        return $this->handler->getMap();
    }

    function hasMap()
    {
        return !is_null( $this->handler );
    }
}