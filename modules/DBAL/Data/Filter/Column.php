<?php
namespace DBAL\Data\Filter;

use DBAL\Data\Entity;

class Column extends \Core\Filter
{
    function execute( array $params = array() )
    {
        $node = $params['input'];

        $sqlAttribute = new Entity\Attribute( $node['Field'], $this->getAttributeType( $node['Type'] ) );
        $length = $this->getAttributeLength( $node['Type'] );

        $sqlAttribute->setLength( $length );

        if( $node['Null'] == 'NO' )
            $flags[] = Entity\Attribute::NotNull;
        if( $node['Key'] == 'PRI')
            $flags[] = Entity\Attribute::PrimaryKey;
        if( $node['Extra'] == 'auto_increment' )
            $flags[] = Entity\Attribute::AutoIncrement;

        if( is_array( $flags ))
            $sqlAttribute->setFlags( $flags );

        if( $node['Default'] == 'NULL' )
            $default = null;
        else
            $default = $node['Default'];

        $sqlAttribute->setDefault( $default );

        return $sqlAttribute;
    }

    protected function getAttributeType( $input )
    {
        $end = strpos( $input, '(' );
        if( $end > 0 )
            $input = substr( $input, 0, $end );

        return $input;
    }

    protected function getAttributeLength( $input )
    {
        $begin = strpos( $input, '(' );
        if( $begin > 0 )
        {
            $begin++;
            $end = strpos( $input, ')' );
            $lenlen = $end - $begin;
            $length = substr( $input, $begin, $lenlen );
        }
        else
            $length = 0;

        return (int)$length;
    }
}