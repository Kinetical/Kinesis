<?php
namespace IO\Context\Option;

class Collection extends \Util\Collection
{
    private $_context;

    function __construct( \IO\Context $context )
    {
        $this->setContext( $context );
        parent::__construct();
    }

    function getContext()
    {
        return $this->_context;
    }

    function setContext( \IO\Context $context )
    {
        $this->_context = $context;
    }
}
