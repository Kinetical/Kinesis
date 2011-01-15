<?php
namespace DBAL\Data;

use \Core\Interfaces as I;

abstract class Model extends \Core\Object implements I\Nameable, I\Attributable, I\Indexable
{
    private $_name;
    private $_index;
    private $_base;
    private $_attributes;

    private $_loaderName;
    private $_loaderAttribute;

    function __construct( $name = null )
    {
            $this->setName( $innerName );
            parent::__construct();
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setName( $name )
    {
        $this->_name = $name;
    }

    function getAttributes()
    {
        return $this->_attributes;
    }

    function getAttribute( $name )
    {
        if( $this->hasAttribute( $name ))
                return $this->_attributes[$name];

        return null;
    }

    function setAttribute( $name, $attr )
    {
        $this->_attributes[ $name ] = $attr;
    }

    function setAttributes( array $attribs )
    {
        foreach( $attribs as $attrib )
            $this->addAttribute( $attrib );
    }

    function addAttribute( $attr )
    {
        $innerName = $attr->getInnerName();

        if( !is_null($attr->getLoadName()) )
            $this->_loaderAttribute = $innerName;

        $this->setAttribute( $innerName, $attr );
    }

    function removeAttribute( $attrName )
    {
        unset( $this->_attributes[ $attrName ]);
    }

    function hasAttribute( $attrName )
    {
        return array_key_exists( $attrName, $this->_attributes );
    }

    function clearAttributes()
    {
        $this->_attributes = array();
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
        return $this->_attributes[ $this->_loaderAttribute ];
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

    function getIndex()
    {
        return $this->_index;
    }

    function setIndex( $idx )
    {
        $this->_index = $idx;
    }

    function hasIndex()
    {
        return ( $this->_index !== null ) ? true : false;
    }
}
