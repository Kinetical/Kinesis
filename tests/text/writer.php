<?php
use IO;
use IO\File;
use IO\Text;
use IO\Stream;

$file = new File('tests\test.ini');

$stream = new File\Stream( $file, Stream::WRITE );

$writer = new File\Writer( $stream );
$text = new Text\Writer( $writer );

$stream->open();

$contents = array(
    '; this is an INI file',
    '[section]',
    'name = value1'
);

foreach( $contents as $line )
{
    $text->writeLine( $line );
}

$stream->close();