<?php
namespace IO\Object;

class Cache extends \IO\File\Cache
{
    protected function load( $name )
    {
        $stream = $this->getStream( $name );
        $reader = new \IO\Serial\Reader( new \IO\File\Reader( $stream ) );
        $filter = new \IO\Object\Filter\Unserialize();
        $params = array();

        $stream->open();
        $params['input'] = $reader->readObject();
        $stream->close();

        return $filter( $params );
    }

    protected function save( $name, $value )
    {
        $stream = $this->getStream( $name , 'w+' );
        $writer = new \IO\Serial\Writer( new \IO\File\Writer( $stream ) );
        $filter = new \IO\Object\Filter\Serialize();

        $params = array( 'input' => $value );
        $stream->open();
        
        $writer->writeObject( $filter( $params ) );

        $stream->close();
    }
}
