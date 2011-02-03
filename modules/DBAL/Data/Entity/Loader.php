<?php
namespace DBAL\Data\Entity;

final class Loader extends \DBAL\Data\Loader
{
    function initialize()
    {
        parent::initialize();

        $this->view = new \DBAL\XML\View\Entity( array( 'path' => 'site\entity.xml') );
    }
    
    function __invoke( $params = null )
    {
        if( is_array( $params ))
            $this->view->Parameters['xpath'] = 'entity[@name="'.$params['name'].'"]';
        else
            unset( $this->view->Parameters['xpath'] );

        return parent::__invoke();
    }
}