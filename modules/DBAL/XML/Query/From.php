<?php
namespace DBAL\XML\Query;

class From extends \DBAL\Query\Node
{
    function create($data)
    {
        $params = array( 'StreamType'       => 'IO\File\Stream',
                         'StreamMode'       => \IO\Stream::READ,
                         'StreamResource'   => new \IO\File( $data ),
                         'StreamWrapper'    => 'IO\File\Reader',
                         'StreamCallback'   => 'readToEOF' );

        $query = $this->getQuery();
        $query->setParameters( $params );
        return parent::create();
    }
}