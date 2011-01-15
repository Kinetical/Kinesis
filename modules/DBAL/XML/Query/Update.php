<?php
namespace DBAL\XML\Query;

class Update extends \DBAL\Query\Node
{
    function create($data)
    {
        $params = array( 'StreamType'       => 'IO\File\Stream',
                         'StreamMode'       => \IO\Stream::WRITE,
                         'StreamResource'   => new \IO\File( $data ),
                         'StreamWrapper'    => 'DBAL\XML\Text\Writer',
                         'StreamCallback'   => 'writeDocument' );

        $query = $this->getQuery();
        $query->setParameters( $params );
        return parent::create();
    }
}