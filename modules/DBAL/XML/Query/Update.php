<?php
namespace DBAL\XML\Query;

class Update extends \Kinesis\Task\Statement
{
    function __construct( $file, Task $parent = null )
    {
        $params = array('File' => $file);

        return parent::__construct( $params, $parent );
    }
    
    function execute()
    {
        $resource = new \IO\File( $this->Parameters['File'] );
        $params = array( 'StreamType'       => 'IO\File\Stream',
                         'StreamMode'       => \IO\Stream\Mode::Write,
                         'StreamResource'   => $resource,
                         'StreamHandler'    => 'IO\File\Writer',
                         'HandlerChain'     => 'DBAL\XML\Text\Writer' );
        
        $this->getQuery()->Parameters += $params;
    }
}