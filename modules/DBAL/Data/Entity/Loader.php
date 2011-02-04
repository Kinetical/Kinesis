<?php
namespace DBAL\Data\Entity;

final class Loader extends \DBAL\Data\Loader
{
    function initialize()
    {
        parent::initialize();

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
            $viewArgs['xpath'] = 'entity[@name="'.$params['name'].'"]';
            $this->parameters['ViewArguments'] = $viewArgs;
        }

        return parent::parse( $params );
    }
}