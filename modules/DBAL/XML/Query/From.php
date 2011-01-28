<?php
namespace DBAL\XML\Query;

class From extends \DBAL\Query\Node
{
    function create($data)
    {
        $params = array( 'StreamType'       => 'IO\File\Stream',
                         'StreamMode'       => \IO\Stream\Mode::Read,
                         'StreamResource'   => new \IO\File( $data ),
                         'StreamHandler'    => 'IO\File\Reader',
                         'StreamCallback'   => 'readToEOF');

        $query = $this->getQuery();

        $query->Filters->register(  new \DBAL\XML\Filter\SimpleXML() );

        $query->setParameters( $params );
        return parent::create();
    }
}