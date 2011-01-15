<?php
namespace DBAL\Data\Type;

class Loader extends \IO\File\PatternLoader
{
    function __construct()
    {
        parent::__construct('modules', true);
    }
    function addDirectory( $path )
    {       
        if( basename( $path ) == 'Type')
            return parent::addDirectory( $path );
    }

    function match( $schematicAttribute )
    {
        $cache = $this->getCache();

        $typeName = $this->getTypeName( $schematicAttribute );

        $qualifiedName = $this->getTypePattern( $schematicAttribute );

        if( array_key_exists( $qualifiedName, $cache ))
            return $cache[ $qualifiedName ];

        $matchedPath = parent::match( $qualifiedName );
            
        $componentPath = $this->getComponentPath( $qualifiedName )."\\".$qualifiedName;

        try
        {
        if( class_exists( $componentPath ))
            $cache[$typeName] = new $componentPath();
        }
        catch( \Exception $e ) {}
        
        return $matchedPath;
    }

    private function getComponentPath( $typeName )
    {
        $parts = explode( "\\", $this->getCachedPath($typeName));
        array_pop( $parts );
        array_shift($parts);

        return implode("\\", $parts);
    }

    private function getTypePattern( \DBAL\Database\SchematicAttribute $schematicAttribute )
    {
        $typeName = $this->getTypeName( $schematicAttribute );


        if( $schematicAttribute instanceof \ORM\Entity\SQLAttribute )
            $qualifiedName = 'SQL'.$typeName;
        else
            $qualifiedName = $typeName;

        return $qualifiedName.'Type';
    }

    private function getTypeName( \DBAL\Database\SchematicAttribute $schematicAttribute )
    {
        return ucfirst($schematicAttribute->getTypeName());
    }

    function load( $path, $args = null )
    {
        if( is_string( $path ))
            return parent::load( $path );

        $cache = $this->getCache();
        if( ($schematicAttribute = $path) instanceof \DBAL\Database\SchematicAttribute
            && $this->match( $schematicAttribute ))
            return $cache[ $this->getTypeName( $schematicAttribute )];

        return false;
    }

    function initialize()
    {
        parent::initialize();

        $this->setExtension('php');
    }
}