<?php
namespace DBAL\Data\Entity;

final class Loader extends \DBAL\Data\Loader
{
    function initialise()
    {
        parent::initialise();

        $params = array('ViewClass' => 'DBAL\XML\View\Entity',
                        'ViewArguments' => array( 'path' => 'site\entity.xml') );

        $this->setParameters($params);
    }
    
    function parse( array $params = null )
    {
        if( is_array( $params ) &&
            array_key_exists('name', $params ))
        {
            if( !$this->parameters->exists('ViewArguments') )
                 $this->parameters['ViewArguments'] = array();

            $viewArgs = $this->parameters['ViewArguments'];
            $query = new \DBAL\XML\Query();
            $query->build()
                  ->where('entity')
                  ->attribute('name', $params['name'] );

            // RETRIEVE XPATH
            $viewArgs['xpath'] = (string)$query;
            $this->parameters['ViewArguments'] = $viewArgs;
        }

        return parent::parse( $params );
    }
}