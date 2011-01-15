<?php
namespace DBAL\Data\Model;

class Loader extends \Core\Configuration\Loader
{
    protected function load( $path, $args = null )
    {
        $cache = $this->getCache();
        list($entityName,) = $this->getInfo( $path ) ;
        
        if( array_key_exists( $entityName, $cache ))
                return $cache[$entityName];

        if( $args instanceof \DBAL\Data\View )
            $view = $args;
        elseif( is_array( $args )
                && array_key_exists('view', $args ))
            $view = $args['view'];

        if( ($schema = parent::load( $path, $view ))
                instanceof \DBAL\Data\Model )
            return $schema;

        throw new \Core\Exception('Model ('.$path.') not found');
    }

    function add( $name, $schema )
    {
         $dataSet = \Core::getInstance()->getDatabase()->getDataSet();
         if( $schema instanceof \DBAL\Data\Model )
             $dataSet->addModel( $schema );

         return parent::add($name, $schema);
    }
}
