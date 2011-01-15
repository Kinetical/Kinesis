<?php
namespace DBAL\Data;

abstract class Loader extends \Core\Loader
{
    private $_source;
    private $_model;
    private $_callback;

    function hasSource()
    {
        return (is_array( $this->_source )
                || $this->_source instanceof \Traversable)
                ? true
                : false;
    }

    function getSource()
    {
        return $this->_source;
    }

    function setSource( $bindSource )
    {
        $this->_source = $bindSource;
    }
    
    function getCallback()
    {
        return $this->_callback;
    }

    function setCallback( $functionName )
    {
        $this->_callback = $functionName;
    }

    function getModel()
    {
        return $this->_model;
    }

    function setModel( Model $model )
    {
        $this->_model = $model;
    }

    function hasModel()
    {
        return ( !is_null($this->_model) ) ? true : false;
    }

    function flush()
    {
        if( $this->hasSource() )
        {
            foreach( $this->getSource() as $item )
            {
                if( ! is_null($item)
                   && ($match = $this->match( $item ) )!== false)
                {
                    $results[] = $this->load( $match, $item );
                }
            }
        }

        return $results;
    }

    protected function getComponentPath( array $data, $loaderAttribute = null )
    {
        if( $loaderAttribute == null )
        {
            $baseModel = $this->getModel()->getBaseModel();
            $loaderAttribute = $baseModel->getLoaderAttribute();
        }

        return $data[ $loaderAttribute->getInnerName() ];
    }

    private function getLoaderPath( $data )
    {
        
        //var_dump( $data );
        $model = $this->getModel();

        $baseModel = $model->getBaseModel();

        $loaderAttribute = $baseModel->getLoaderAttribute();

        $componentPath = $this->getComponentPath( $data, $loaderAttribute );
        $loadModel = $model->getLoaderSchematic( $componentPath );
        $attributeLoad = $loaderAttribute->getLoadName();
        $componentName = $loadModel->$attributeLoad;

        return array( $loadModel, $componentName );
    }

    function match( $data )
    {
        if( $this->hasModel() )
        {
        list( $loadSchematic, $componentName ) = $this->getLoaderPath( $data );

        $core = \Core::getInstance();

        if( $core->hasComponent( $componentName, '' ) )
        {
            $module = $core->getComponentModule( $componentName, '');
            return $module->matchesComponent( $componentName, '');
        }
        }
        return false;
    }

    function load( $path, $args = null )
    {
        $object = new $path();
        $object = $this->bind( $object, $args );

        return $object;
    }

    protected function bind( $object, $args = null )
    {
        if( $this->hasModel() )
        {
            $model = $this->getModel();
            $loadModel = $model->getLoaderSchematic( $this->getComponentPath( $args ) );
            $object->Type->persist( $loadModel );

            $attributes = $model->getAttributes();

            foreach( $attributes as $attr )
            {
                $innerName = $attr->getInnerName();

                if( array_key_exists( $innerName, $args )
                    && !$schematic->hasRelation( $innerName )
                    && ($dataType = $attr->getDataType()) instanceof \DBAL\DataType )
                {
                    $args[ $innerName ] = $dataType->toGeneric( $args[ $innerName ]);
                }
            }
        }

        if( $this->_callback !== null
                && method_exists( $obj, $this->_callback ) )
                $object->{$this->_callback}( $args );
        elseif( $object instanceof \Core\Object )
            $object->Data = $args;

        return $object;
    }
}