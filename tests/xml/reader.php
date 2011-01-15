<?php
use IO;
use IO\File;
use IO\Text;

$file = new File('tests\test.xml');

$stream = new File\Stream( $file );

$reader = new File\Reader( $stream );

$doc = new DOMDocument();

$stream->open();
$doc->loadXML( $reader->readToEOF() );
$stream->close();

var_dump( $doc );


