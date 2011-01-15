<?php
namespace ORM;

abstract class Hydrator extends \DBAL\Query\Filter
{
    private $_resource;
    private $_dataType;
    
    function __construct( $resource )
    {
        if( $resource instanceof \Core\Resource )
            $this->_resource = $resource;
        else
            throw new \InvalidArgumentException ('Hydrator constructor must have object of type \Core\Resource passed to it', '$resource');

            parent::__construct();
    }

    function getResource()
    {
        return $this->_resource;
    }

    protected function setResource( \Core\Resource $resource )
    {
        $this->_resource = $resource;
    }

    protected function getMappers()
    {
        return $this->_resource->getMappers();
    }

    function getDataType()
    {
        if(   is_null( $this->_dataType )
           && $this->_resource->hasQuery())
              $this->_dataType = $this->_resource
                                      ->getQuery()
                                      ->getDataType();

        return $this->_dataType;
    }

    function setDataType( $type )
    {
        $this->_dataType = $type;
        if(   $this->_resource instanceof \Core\Resource
           && $this->_resource->hasQuery() )
              $this->_resource->getQuery()->setDataType( $type );
    }

    protected function getSchematic()
    {
        
        return $this->getModel();
    }

    function getModel()
    {
        if( !$this->hasModel() )
            $this->setModel( $this->_resource->getModel() );

        return parent::getModel();
    }

    function setSchematic( \DBAL\Database\Schematic $schematic )
    {
        return parent::setModel( $schematic );
    }

    function hasSchematic()
    {
        return parent::hasModel();
    }

    function getComponentPath( array $data, $loaderAttribute = null )
    {
        if( $loaderAttribute == null )
        {
            $baseSchematic = $this->getSchematic()->getBaseSchematic();
            $loaderAttribute = $baseSchematic->getLoaderAttribute();
        }

        return $data[ $loaderAttribute->getInnerName() ];
    }

    function match( $path )
    {
        $dataType = $this->getDataType();
            
        if( class_exists( $dataType, true ))
        {
            echo $dataType;
            return $dataType;
        }
        elseif( ($classPath = parent::match( $path ) ) !== false )
            return $classPath;
        
        return false;
    }

    function load( $path, $args = null )
    {
        $object = parent::load( $path, $args );
        $object = $this->map( $object );

        if( $object instanceof \Core\Object
            && $object->Type->isPersisted()
            && $object->Type->isPersistedBy('EntityObject'))
            {
                $database = \Core::getInstance()->getDatabase();
                if( $database->getContext()->getLoading() == \DBAL\DataContext::Immediate )
                {
                    $immediateLoader = new \ORM\Entity\Relationship\Loader\ImmediateLoader( $object );
                    $immediateLoader->load( null );
                }
            }

        return $object;
    }

    protected function map( $object )
    {
        if( $this->_resource->isMapped() )
        {
            $mappers = $this->_resource->getMappers();

            foreach( $mappers as $mapper )
                $object = $mapper->map( $object );
        }

        return $object;
    }

    function getSource()
    {
        if( !$this->hasSource()
            && $this->_resource->hasQuery() )
            $this->setSource( $this->_resource->getQuery()->getResults() );

        return parent::getSource();
    }
}
