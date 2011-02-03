<?php

$c = 0;
for( $i = 0; $i < 100000; $i++ )
{
    $d = rand( 1, 6 );
    $c = rand( 1, 2 );

    if( $d == 1 &&
        $c == 2 )
    {
        $c++;
    }
}

echo $c.'/'.$i;