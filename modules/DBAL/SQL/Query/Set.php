<?php
namespace DBAL\SQL\Query;

class Set extends \DBAL\Query\Node
{
    function create( $data )
    {
        if( is_array( $data ))
        {
            if( $this->ModelNode instanceof \DBAL\SQL\Query\Update )
            {
                    foreach( $this->Model->Attributes as $attr )
                    {
                            if( array_key_exists( $attr->OuterName, $data )
                                    && !$this->Model->hasRelation( $attr->InnerName ))
                            {
                                    $value = $data[ $attr->OuterName ];
                                    if( $value instanceof Object
                                            && $value->Type->isPersisted()
                                            && $value->Type->isPersistedBy('EntityObject')
                                            && $value->Type->getPersistenceObject()->PrimaryKey !== null )
                                            {
                                                    $primaryKey = $value->Type->getPersistenceObject()->PrimaryKey->OuterName;
                                                    if( $value->$primaryKey !== null )
                                                            $value = $value->$primaryKey;
                                            }

                                    $innerData[ $attr->InnerName ] = $value;
                            }
                    }
            }
            elseif( $this->ModelNode instanceof \DBAL\SQL\Query\Insert )
            {
                    if( $this->QueryBuilder->hasNode('set')
                            && $this->QueryBuilder->Nodes['set']->Oid !== $this->Oid )
                    {
                            $this->QueryBuilder->Nodes['set']->create( $data );
                            return false;
                    }


                    if( count( $data ) == count( $data, COUNT_RECURSIVE ) )
                            $data = array( $data );

                    $innerData = $data;
            }
        }

        if( is_array( $this['innerData'] ))
                $innerData = array_merge( $this['innerData'], $innerData );

        $this['innerData'] = $innerData;

        //TODO: single instance of set per querybuilder, multiple sets are appended to first
        return parent::create();
    }

    function open()
    {
            if( $this->ModelNode instanceof \DBAL\SQL\Query\Update )
            {
                    $sql  = "SET ";

                    $count = 1;

                    $alias = $this->Model->Alias;
                    foreach( $this['innerData'] as $name => $value )
                    {
                            $sql .= $alias.'.'.$name.' = ';
                            if( is_string( $value ))
                            $sql .= "'";
                            $sql .= $value;
                            if( is_string( $value ))
                            $sql .= "'";
                            if( $count < count( $this['innerData'] ) )
                                    $sql .= ",\n";
                            $count++;
                    }

                    $sql .= "\n";
            }
            elseif( $this->ModelNode instanceof \DBAL\SQL\Query\Insert )
            {
                    $sql = " (";
                    $attributes = $this->Model->Attributes;
                    //unset( $attributes[ $this->Entity->PrimaryKey->InnerName ] );
                    $names = array_keys( $attributes );

                    for( $i = 0; $i < count( $names ); $i++ )
                    {
                            if( $i > 0 )
                                    $sql .= ',';
                            $sql .= '`'.$this->Entity->Attributes[ $names[$i] ]->OuterName.'`';
                    }

                    $sql .= " ) VALUES ";

                    $index = array_keys( $this['innerData'] );

                    for( $i = 0; $i < count( $index ); $i++ )
                    {

                            if( $i > 0 )
                                            $sql .= ',';

                            $sql .= ' ( ';
                            $row = $this['innerData'][ $index[$i] ];
                            if( $row instanceof Object )
                                    $row = $row->Data;

                            for( $c = 0; $c < count( $names ); $c++ )
                            {
                                    $attr = $this->Entity->Attributes[ $names[$c] ];
                                    if( $c > 0 )
                                            $sql .= ',';



                                    $value = $row[$attr->OuterName];

                                    if( $attr->IsPrimaryKey()
                                            && $value == null )
                                            $value = 0;

                                    if( is_string( $value )
                                            || $value === null )
                                            $sql .= "'";
                                    $sql .= $value;
                                    if( is_string( $value )
                                            || $value === null)
                                            $sql .= "'";
                            }
                            $sql .= ' ) ';
                    }
            }

            return $sql;
    }
}