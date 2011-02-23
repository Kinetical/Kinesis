<?php
namespace DBAL\SQL\Query;

class Create extends Container
{
    function __construct( $table, \Kinesis\Task $parent )
    {
        parent::__construct( array('Table' => $table), $parent );
    }
    
    function initialise()
    {
        $table = $this->Parameters['Table'];
        if( $table instanceof \DBAL\Data\Entity )
        {
            foreach( $table->Attributes as $attr )
            {
                $this->Children[] = new Attribute( $attr, $this );
            }
            
            $this->Parameters['Table'] = $table->getName();
            
            if( $table->hasAlias() )
                $this->Parameters['Alias'] = $table->getAlias();
            
            if( $table->PrimaryKey instanceof \DBAL\Entity\Attribute )
                $this->Parameters['PrimaryKey'] = $table->PrimaryKey->Name;
        }
    }
    
    function execute()
    {
        extract( $this->Parameters );
        $platform = $this->getPlatform();
        
        $query = $platform->create( $Table );
        if( isset( $Alias ))
            $query .= $platform->alias( $Alias );
        
        $query .= '(';
        $query .= parent::execute();
        //TODO: DELIMIT ATTRIBUTES WITH COMMA
        
        if( isset( $PrimaryKey ))
            $query .= $platform->primaryKey( $PrimaryKey );
        
        $query .= ')';
        //TODO: ENGINE
        return $query;
    }
}