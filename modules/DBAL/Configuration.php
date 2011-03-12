<?php
namespace DBAL;

class Configuration extends \Core\Configuration
{
    
    function __construct( $loader = null )
    {
        if( is_null( $loader ))
        {
            $params = array('CacheClass' => 'IO\Object\Cache',
                            'ViewClass' => 'DBAL\XML\View',
                            'ViewArguments' => array( 'path' => 'site/database.xml' ) );

            $loader = new Data\Loader( $params );
        }
        
        parent::__construct( $loader );
    }

    protected function loader( $offset )
    {
        $view = $this->loader->getView();
        $view->Parameters['xpath'] = $offset;

        return parent::loader( $offset );
    }

    function getLogger()
    {
        return $this['database/logger'];
    }

    function getDatabase()
    {
        return $this['database'];
    }

    function getUser()
    {
        return $this['database/user'];
    }
}