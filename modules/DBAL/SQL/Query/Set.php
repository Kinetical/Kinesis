<?php
namespace DBAL\SQL\Query;

class Set extends Statement
{
    function __construct( $name, $value = null, \Kinesis\Task $parent )
    {
        if( is_array( $name ) &&
            is_null( $value ))
            $params = array( 'Data' => $name );
        elseif( is_scalar( $name ) &&
                !is_null( $value ))
            $params = array( 'Data' => array( $name => $value ) );
        
        $parent->Parameters['Container']->addChild( $this );
        
        parent::__construct( $params, $parent->Parameters['Container'] );
    }
    
    function initialise()
    {
        $data = $this->Parameters['Data'];

        if( $this->Parent instanceof Update )
        {
            if( count( $data ) !== count( $data, COUNT_RECURSIVE ) )
                $data = array( $data );
            
            $this->Parameters['Data'] = $data;
        }
        elseif( $this->Parent instanceof Insert )
        {
            $this->Parameters['Attributes'] = $this->Parent->getTable()->getAttributes();
            //TODO: RETRIEVE RELATIONSHIPS
        }
    }
    
    function execute()
    {
        $platform = $this->getPlatform();
        $table = $this->Parent->getTable();
        $data = $this->Parameters['Data'];
        
        if( $table->hasAlias() )
            $alias = $table->getAlias();
        
        if( $this->Parent instanceof Update )
        {
            $q = $platform->set();
            $clauses = array();
            foreach( $data as $key => $value )
            {
                if( !is_null( $value ))
                {
                    $column = $platform->identifier( $key, $alias );

                    $clauses[] = $platform->clause( $column, $value );
                }
            }
            
            $q .= implode( ',', $clauses );
        }
        elseif( $this->Parent instanceof Insert )
        {
            $attributes = $this->Parameters['Attributes'];
            $columns = array();
            $values = array();
            $key = false;
            
            foreach( $attributes as $attr )
            {
                $name = $attr->getName();
                $value = $data[$name];
                if( !is_null( $value ))
                {
                    $columns[$name] = $platform->identifier( $name );

                    if( $attr->isPrimaryKey() )
                    {
                        $values[] = 0;
                        $key = true;
                    }
                    else
                        $values[] = $platform->value( $value );
                }
            }
            
            if( !$key && 
                !array_key_exists('id',$columns))
            {
                array_unshift( $columns, $platform->identifier( 'id' ) );
                array_unshift( $values, "''");
            }
                
            $q = $platform->values( $columns, $values );
        }
        
        return $q;
    }
}