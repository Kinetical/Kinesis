<?php
namespace DBAL\XML\Data;

class Source extends \DBAL\Data\Source
{
    private $_xpath;

    public function getIterator() {

        return new \DBAL\Data\Tree\Node\Iterator( $this->Data );
    }

    function __construct( $xpath = null )
    {
        parent::__construct();
        parent::setDataType('DBAL\Data\Tree\Node');

        $this->_xpath = $xpath;
    }

    function getXPath()
    {
        return $this->_xpath;
    }
}