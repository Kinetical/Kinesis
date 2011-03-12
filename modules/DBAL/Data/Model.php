<?php
namespace DBAL\Data;

use \Util\Interfaces as I;

abstract class Model implements I\Nameable, I\Attributable
{
    protected $attributes;
    public $Attributes;
    public $Name;

    private $_base;

    private $_loaderName;
    private $_loaderAttribute;

    function __construct( $name = null, array $attributes = array() )
    {
        $this->setName( $name );

        $this->Attributes = new Model\Attribute\Collection( $this, $attributes );
    }

    public function getName()
    {
        return $this->Name;
    }

    public function setName( $name )
    {
        $this->Name = $name;
    }

    function getAttributes()
    {
        return $this->Attributes;
    }

    function setAttributes( array $attributes )
    {
        $this->Attributes->merge( $attributes );
    }

    function hasLoader()
    {
        return ( $this->_loaderName !== null ) ? true : false;
    }
    function getLoaderName()
    {
        if( $this->_loaderName == null )
            $this->_loaderName = 'entity';
        return strtolower($this->_loaderName);
    }
    function setLoaderName( $name )
    {
        $this->_loaderName = $name;
    }
    function getLoaderAttribute()
    {
        return $this->_loaderAttribute;
    }

    function setLoaderAttribute( Model\Attribute $attr )
    {
        $this->_loaderAttribute = $attr;
    }

    protected function getLoader()
    {
        $loaders = \Core::getInstance()->getDatabase()->getLoaders();

        return $loaders[ $this->getLoaderName() ];
    }

    function getLoaderModel( $componentPath = null )
    {
        $model = $this->getBaseModel();
        $loaderAttribute = $model->getLoaderAttribute();

        $loader = $model->getLoader();
        if( $loader instanceof Loader )
            if( $loader->has( $componentPath ))
                return $loader->get( $componentPath );
            elseif( $componentPath !== $model->getName()
                    && $loader->match( $componentPath ))
                return $loader->load( $componentPath );

        return null;
    }

    function getBaseModel()
    {
        if( is_null( $this->_base ) )
            return $this;

        return $this->_base;
    }

    function setBaseModel( Model $model )
    {
        return $this->_base = $model;
    }

    function hasBaseModel()
    {
        return ( !is_null($this->_model) ) ? true : false;
    }
}
