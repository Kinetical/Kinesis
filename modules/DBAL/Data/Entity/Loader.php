<?php
namespace DBAL\Data\Entity;

final class Loader extends \DBAL\Data\Model\Loader
{
    function initialize()
    {
        parent::initialize();
        $this->setFileName('entity');
    }
    
    function load( $path, $args = null )
    {
        list($entityName,) = $this->getInfo( $path ) ;

        $view = new \DBAL\View\XMLEntityView($entityName);

        return  parent::load( $path, $view );
    }
}