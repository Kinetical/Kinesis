<?php
namespace IO\Filter\Delegate;

class Iterator extends \IO\Filter\Iterator
{
    protected $delegate;
    protected $handler;

    protected $output;
    protected $input;


    function __construct( \Core\Delegate $delegate = null )
    {
        $this->delegate = $delegate;

        parent::__construct();
    }

    function initialize()
    {
        parent::initialize();

        $this->input = new \Util\Collection();
    }

    function hasDelegate()
    {
        return !is_null( $this->delegate ) ;
    }

    function position( $position = null )
    {
        if( is_int( $position ))
            return parent::position( $position );
        
        if( $this->isShared() )
            return 0;

        return parent::position();
    }

    protected function isFiltered( $state = \IO\Filter::OUTPUT )
    {
        if( $this->hasMap() )
            if( $this->isShared() &&
                $this->position > parent::position() )
                return true;
            else
                return true;

        return false;
    }

    public function current()
    {
        $delegate = $this->delegate;

        $buffer = $delegate( $input );

        return parent::current( $buffer );
    }

    function rewind()
    {
        unset($this->output);
        return parent::rewind();
    }

    function valid()
    {
        if( $this->position < $this->input->count() )
            return true;

        return false;
    }

    protected function getTarget()
    {
        return $this->delegate->getTarget();
    }

    function getDelegate()
    {
        return $this->delegate;
    }

    protected function setDelegate( \Core\Delegate $delegate )
    {
        $this->delegate = $delegate;
    }
}