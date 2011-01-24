<?php
namespace DBAL\XML\Query;

class Update extends \DBAL\Query\Node
{
    function create($data)
    {
        $params = array( 'StreamType'       => 'IO\File\Stream',
                         'StreamMode'       => \IO\Stream\Mode::Write,
                         'StreamResource'   => new \IO\File( $data ),
                         'StreamHandler'    => 'IO\File\Writer',
                         'HandlerChain'     => 'DBAL\XML\Text\Writer' );

        $query = $this->getQuery();
        $query->setParameters( $params );
        return parent::create();
    }
}