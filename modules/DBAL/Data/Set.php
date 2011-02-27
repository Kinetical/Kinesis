<?php
namespace DBAL\Data;

// TODO: EXTEND CORE\MANAGER
class Set extends \Kinesis\Object
{
    private $_tables;
    private $_models;
    private $_typeLoader;

    function initialise()
    {
        $this->_typeLoader = new Loader\TypeLoader();
    }

    function clear()
    {

    }

    function hasChanges()
    {

    }

    function getChanges()
    {

    }

    function getTypeLoader()
    {
        return $this->_typeLoader;
    }

    function getModels()
    {
        return $this->_models;
    }

    function setModels( array $models )
    {
        $this->_models = $models;
    }

    function addSchematic( Model $model )
    {
        return $this->_models[$model->getName()] = $model;
    }

    function getModel( $name )
    {
        return $this->_models[$name];
    }

    function removeModel( $name )
    {
        unset( $this->_model[ $name ]);
    }

    function hasModel( $name )
    {
        if(  !array_key_exists( $name, $this->_models ) )
        {
            $dataBase = \Core::getInstance()->getDatabase();
             try
             {
                 if( ($model = $dataBase->load( $name ))
                     instanceof Model )
                     return $this->addModel( $name );
                 /*$loaders = $dataBase->getLoaders();

                 $loader = $dataBase->getLoader( $loaderName );

                 if( $loader instanceof \Core\Loader )
                 {
                     $schema = $loader->loadPath( $schemaName );
                     if( $schema instanceof Database\Schematic )
                         return true;
                 }*/
             }
             catch( \Core\Exception $e )
             {
                    return false;
             }
        }

        return array_key_exists( $name, $this->_models );
    }

    function getTables()
    {
        return $this->_tables;
    }

    function setTables( array $tables )
    {
        foreach( $tables as $tbl )
            $this->addTable( $tbl );
    }

    function addTable( \DBAL\DataTable $table )
    {
        $this->_tables[$table->OuterName] = $table;
    }

    function removeTable( $tableName )
    {
        unset( $this->_tables[$table->OuterName]);
    }

    function hasTable( $tableName )
    {
        return array_key_exists( $tableName, $this->_tables );
    }
}
