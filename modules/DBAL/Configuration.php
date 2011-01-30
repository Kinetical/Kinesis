<?php
namespace DBAL;

class Configuration extends \Core\Configuration
{
    function initialize()
    {
        parent::initialize();

        $params = array('CacheClass' => 'IO\Object\Cache',
                        'ViewClass' => 'DBAL\XML\View',
                        'ViewArguments' => array( 'path' => 'site\database.xml' ) );

        $loader = new Data\Loader( $params );
        $this->setLoader( $loader );
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