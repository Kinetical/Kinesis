<?php
namespace DBAL\XML\Query;

class From extends \Kinesis\Task\Statement
{
    public $File;

    function __construct( $file, \Kinesis\Task $parent )
    {
        $this->File = $file;
       
        parent::__construct( null, $parent );
    }
    function execute()
    {
        $params = array( 'StreamType'       => 'IO\File\Stream',
                         'StreamMode'       => \IO\Stream\Mode::Read,
                         'StreamHandler'    => 'IO\File\Reader',
                         'StreamCallback'   => 'readToEOF',
                         'StreamResource'   => new \IO\File( $this->File ));
        
        $this->getQuery()->Parameters += $params;
    }
}