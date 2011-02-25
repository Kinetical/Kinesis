<?php
namespace DBAL\Platform;

class MySQL extends \DBAL\Platform
{
    function select( $table )
    {
        return 'SELECT '.$table;
    }
    
    function update( $table )
    {
        return 'UPDATE '.$table;
    }
    
    function delete()
    {
        return 'DELETE ';
    }
    
    function identifier( $id, $alias = null )
    {
        if( !is_null( $alias ))
            $q = $alias.'.';
        
        $q .= '`'.$id.'`';
        return $q;
    }
    
    function drop()
    {
        return ' DROP ';
    }
    
    function join( $table )
    {
        return ' JOIN '.$table;
    }
    
    function on( $table, $column )
    {
        return ' ON '.$table.' = '.$column;
    }
    
    function insert( $table )
    {
        return 'INSERT INTO '.$this->identifier( $table );
    }
    
    function values( array $columns, array $values )
    {
        $q .= ' (';
        $q .= implode(',', $columns );
        $q .= ' ) VALUES ( ';
        $q .= implode(',', $values );
        $q .= ' ) ';
        
        return $q;
    }
    
    function table( $table )
    {
        return ' TABLE '.$this->identifier( $table );
    }
    
    function create( $table )
    {
        return 'CREATE '.$this->table( $table );
    }
    
    function alter( $table )
    {
        return 'ALTER '.$this->table( $table );
    }

    function from( $table )
    {
        return ' FROM '.$table;
    }
    
    function alias( $alias )
    {
        return ' AS '.$alias;
    }

    function where( $column, $value, $operator = '=' )
    {
        return ' WHERE '.$this->clause( $column, $value, $operator );
    }
    
    function clause( $column, $value, $operator = '=')
    {
        return $column.' '.$operator.' '.$this->value( $value );
    }
    
    function value( $value )
    {
        if( is_string( $value ))
            $value = "'".$value."'";
        
        return $value;
    }
    
    function change( $name )
    {
        return ' CHANGE '.
               $this->identifier( $name );
    }
    
    function bitwiseAnd()
    {
        return ' AND ';
    }
    
    function bitwiseOr()
    {
        return ' OR ';
    }
    
    function add()
    {
        return ' ADD ';
    }
    
    function column( $column, $type, $length = 0, $default = null, array $flags = null )
    {
        $q = $this->identifier( $column ).
             ' '.$type;
        
        if( $length > 0 )
            $q = '('.$length.')';
        if( !empty( $flags ))
            $q = $this->flags( $flags );
        if( !is_null( $default ))
            $q = ' DEFAULT '. $default;
        
        return $q;
    }
    
    function show()
    {
        return ' SHOW ';
    }
    
    function columns()
    {
        return ' COLUMNS ';
    }
    
    function tables()
    {
        return ' TABLES ';
    }
    
    function primaryKey( $key )
    {
        return ' PRIMARY KEY ('.$key.')';
    }
    
    function set()
    {
        return ' SET ';
    }
    
    function beginTransaction()
    {
        return 'START TRANSACTION;';
    }
    
    function applyTransaction()
    {
        return 'COMMIT;';
    }
    
    function undoTransaction()
    {
        return 'ROLLBACK;';
    }
}