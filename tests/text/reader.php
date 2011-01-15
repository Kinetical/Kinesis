<?php
use IO;
use IO\File;
use IO\Text;

$file = new File('tests\test.ini');

$stream = new File\Stream( $file );

$reader = new Text\Reader( $stream );

$stream->open();

while( ($buffer = $reader->readLine()) !== false )
{
    echo $buffer;
    echo '<br/>';
}

$stream->close();
