<?php

$encoding = mb_list_encodings();


foreach( $encoding as $en )
{

    echo "const ".strtoupper( str_replace('-', '_', $en ) )." \t\t= '".$en."';";
    echo "\n";
}