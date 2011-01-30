<?php
namespace DBAL\Data;

class Loader extends \Core\Loader
{
    protected $adapter;
    protected $view;

    function getView()
    {
        if( is_null( $this->view ))
            $this->view = $this->getDefaultView();
        
        return $this->view;
    }

    function setView( \DBAL\Data\View $view )
    {
        $this->view = $view;
    }

    function getAdapter()
    {
        if( is_null( $this->adapter ) )
            $this->adapter = $this->getDefaultAdapter();
        return $this->adapter;
    }

    function setAdapter( Adapter $adapter )
    {
        $this->adapter = $adapter;
    }

    protected function getDefaultAdapter()
    {
        if( $this->parameters->exists('AdapterClass'))
            $adapterClass = $this->parameters['AdapterClass'];
        else
            $adapterClass = 'DBAL\Data\Adapter';

        return new $adapterClass();
    }

    protected function getDefaultView()
    {
        if( $this->parameters->exists('ViewClass'))
        {
            $viewClass = $this->parameters['ViewClass'];
            $viewArgs = $this->parameters['ViewArguments'];
           
            return new $viewClass( $viewArgs );
        }

        throw new \DBAL\Exception('Unable to load view for ('.get_class( $this ).')');
    }

    protected function parse( array $params = null )
    {
        if( !array_key_exists('view', $params ))
            $params['view'] = $this->getView();
        else
            $this->view = $params['view'];

        if( !array_key_exists('name', $params ))
            $params['name'] = $this->view->getName();

        return $params;
    }

    protected function execute( array $params = null )
    {
        $view = $params['view'];
        $name = $params['name'];

        if( $this->caching()
            && $this->cache->exists( $viewName ))
             return $this->cache[ $viewName ];

        $dataSource  = new \DBAL\Data\Source();
        $dataAdapter = $this->getAdapter();
        $dataAdapter->setView( $view );

        $dataAdapter->Fill( $dataSource );

        unset( $this->view );

        if( !empty( $dataSource ) )
        {
            if( count( $dataSource ) == 1 )
                $result = $dataSource[0];
            else
                $result = $dataSource->toArray();

            if( is_string( $name )
                && $this->caching() )
                return $this->cache[$name] = $result;
            else
                return $result;
        }

        throw new \Core\Exception('Unable to execute path('.$path.') in '.get_class( $this ));
    }
}
