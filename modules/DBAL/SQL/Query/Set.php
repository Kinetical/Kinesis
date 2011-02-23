<?php
namespace DBAL\SQL\Query;

class Set extends Statement
{
    function __construct( array $data, \Kinesis\Task $parent )
    {
        $parent->Parameters['Container']->addChild( $this );
        parent::__( array('Data'=>$data), $parent->Parameters['Container'] );
    }
    
    function initialise()
    {
        $data = $this->Parameters['Data'];

        if( $this->Parent instanceof Update )
        {
            if( count( $data ) == count( $data, COUNT_RECURSIVE ) )
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
                $column = $this->identifier( $key, $alias );
                
                $clauses[] = $this->clause( $column, $value );
            }
            
            $q .= implode( ',', $clauses );
        }
        elseif( $this->Parent instanceof Insert )
        {
            $attributes = $this->Parameters['Attributes'];
            $columns = array();
            $values = array();
            
            foreach( $attributes as $name => $attr )
            {
                $columns[] = $platform->identifier( $name );
                
                if( $attr->isPrimaryKey() )
                    $values[] = 0;
                else
                    $values[] = $platform->value( $data[$name] );
            }
            
            $q = $platform->values( $columns, $values );
        }
        
        return $q;
    }
}