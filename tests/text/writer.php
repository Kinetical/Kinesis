<?php
use IO;
use IO\File;
use IO\Text;
use IO\Stream;

$file = new File('tests\test.ini');

$stream = new File\Stream( $file, Stream::WRITE );

$writer = new Text\Writer( $stream );

$stream->open();

$contents = array(
    '; this is an INI file',
    '[section]',
    'name = value1'
);

foreach( $contents as $line )
{
    $writer->writeLine( $line );
}

$stream->close();