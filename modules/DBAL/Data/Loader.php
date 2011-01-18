<?php
namespace DBAL\Data;

class Loader extends \Core\Loader
{
    protected $adapter;

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
            $adapterClass = 'Adapter';

        return new $adapterClass();
    }

    protected function getDefaultView()
    {
        if( $this->parameters->exists('AdapterView'))
        {
            $viewClass = $this->parameters['AdapterView'];
            $viewArgs = $this->parameters['ViewArguments'];

            $reflection = new \ReflectionClass( $viewClass );
            return $reflection->newInstanceArgs( $viewArgs );
        }

        throw new \DBAL\Exception('Unable to load view for ('.get_class( $this ).')');
    }

    protected function parseArguments( $args )
    {
        if( $args instanceof \DBAL\Data\View )
            $view = $args;
        elseif( is_array( $args )
                && array_key_exists('view', $args ))
            $view = $args['view'];
        else
            $view = $this->getDefaultView();

        return $view;
    }

    protected function execute( $path, $args = null )
    {
        if( array_key_exists( $path, $this->cache ))
            return $this->cache[ $path ];

        $view = $this->parseArguments( $args );

        $dataSource  = new \DBAL\Data\Source();
        $dataAdapter = $this->getAdapter();
        $dataAdapter->setView( $view );

        $dataAdapter->Fill( $dataSource );

        if( !empty( $dataSource ) )
            return $this->cache[$path] = $dataSource[0];

        throw new \Core\Exception('Unable to execute path('.$path.')');
    }
}
