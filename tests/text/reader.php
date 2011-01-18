<?php
use IO;
use IO\File;
use IO\Text;

$file = new File('tests\test.ini');

$stream = new File\Stream( $file );

$reader = new File\Reader( $stream );
$text = new Text\Reader( $reader );

$stream->open();

while( ($buffer = $text->readLine()) !== false )
{
    echo $buffer;
    echo '<br/>';
}

$stream->close();
