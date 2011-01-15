<?php
namespace DBAL\XML;

class View extends \DBAL\Data\View
{
    private $_filePath;

    function __construct( $filePath )
    {
        $this->_filePath = $filePath;

        parent::__construct();
    }

    function initialize()
    {
        parent::initialize();

        $command = new Query();
        $this->setCommand( $command->build()->from( $this->_filePath ));
    }

    function prepare()
    {
        $command = $this->getCommand();
        
        new Filter\SimpleXML( $command );
        new Filter\Node( $command );
        
        parent::prepare();
    }
}