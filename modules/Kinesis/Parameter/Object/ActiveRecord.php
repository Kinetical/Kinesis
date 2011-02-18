<?php
namespace Kinesis\Parameter\Object;

class ActiveRecord extends Control
{

    function save( &$ref )
    {
        $this->insert( $ref );
    }

    private function update( $src )
    {

    }

    private function insert( $src )
    {
        $qry = 'INSERT INTO Users
                (';
        $c = 0;
        foreach( $this->Type->Attributes as $name => $attr )
        {
            if( $c > 0 )
                $qry .= ' , ';

            $qry .= '`'.$attr.'`';

            $c++;
        }
        $qry .= ' )
                 VALUES
                 ( ';

        $c = 0;
        foreach( $this->Type->Attributes as $name => $attr )
        {
            if( $c > 0 )
                $qry .=', ';

            $qry .= "'%s'";
                    $c++;
        }

        $qry .= ' ) ';

        $source = array();

        foreach( $this->Type->Attributes as $name => $attr )
        {
            $value = $src->$attr;

            if( is_null( $value ))
                $value = '';

            $source[] = $value;
        }

        $qry = vsprintf( $qry, $source );

        // HERE WE mysql_query
        // AND RETURN
        echo $qry;
    }

    function delete( $src )
    {

    }

    function find( $id, $src )
    {

    }
}
