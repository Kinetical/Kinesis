<?php
namespace Core\Configuration;

class Loader extends \IO\Serial\Loader
{
    private $_fileName;

    function __construct( $fileName = 'config' )
    {
        $this->_fileName = $fileName;

        parent::__construct('site');
    }

    function initialize()
    {
        parent::initialize();

        $this->setExtension('xml');
    }

    function getFileName()
    {
        return $this->_fileName;
    }

    function getName()
    {
        return $this->getFileName();
    }

    function setName( $idx )
    {
        $this->setFileName( $idx );
    }

    function setFileName( $fileName )
    {
        $this->_fileName = $fileName;
    }

    protected function match( $path )
    {
        $cache = $this->getCache();
        list($entityName,) = $this->getInfo( $path ) ;
        if( array_key_exists( $entityName, $cache ))
                return $entityName;

        if( file_exists( $this->parse($path) ))
            return $entityName;
        
        return false;
    }
    // loadPath( '
    protected function getInfo( $path )
    {
        $pathParts = explode(DIRECTORY_SEPARATOR, $path);

        $name = array_pop( $pathParts );

        if( count( $pathParts ) > 0 )
            $fullPath = DIRECTORY_SEPARATOR.
                        implode(DIRECTORY_SEPARATOR, $pathParts ).
                        DIRECTORY_SEPARATOR;
        else
            $fullPath = DIRECTORY_SEPARATOR;


        return array( $name, $fullPath );
    }

    protected function parse( $path )
    {
        list(, $path) = $this->getInfo( $path ) ;
        
        return parent::parse($path.$this->_fileName);
    }

    protected function load( $path, $args = null )
    {
        $cache = $this->getCache();
        if( array_key_exists( $path, $cache ))
            return $cache[ $path ];

        if( $args instanceof \DBAL\DataView )
            $view = $args;
        elseif( is_array( $args )
                && array_key_exists('view', $args ))
            $view = $args['view'];

        $fullPath = $this->parse( $path );
        $xmlDataSource    = new \DBAL\DataSource\XMLDataSource();
        $xmlAdapter       = new \DBAL\Adapter\XMLDataAdapter( $fullPath );

        if( $view == null )
            $xmlAdapter->View = new \DBAL\View\XMLConfigView( $path );
        else
            $xmlAdapter->View = $view;

        $xmlAdapter->Fill( $xmlDataSource );
        
        if( !empty( $xmlDataSource ) )
        {
            $cache[$path] = ($result = $xmlDataSource[0]);
            return $result;
        }

        throw new \Core\Exception('Configuration directive('.$path.') not found in '.$fullPath);
    }
}