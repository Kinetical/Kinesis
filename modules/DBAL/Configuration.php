<?php
namespace DBAL;

class Configuration extends \Core\Configuration
{
    function initialize()
    {
        parent::initialize();

        $loader = new Data\Loader( array('CacheClass' => 'IO\Object\Cache' ));
        $loader->setView( new XML\View( array( 'path' => 'site\database.xml' ) ) );

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