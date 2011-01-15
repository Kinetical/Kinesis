<?php
namespace DBAL\SQL\Data\Model;

class Adapter extends \DBAL\SQL\Data\Adapter
{
	private $_database;

	function __construct()
	{
            parent::__construct();
	}

	function getDatabase()
	{
		return $this->_database;
	}

	function setDatabase( SQLDatabase $database )
	{
		$this->_database = $database;
	}

	function Fill( SqlDataSource $dataSource )
	{
		$this->Database = $dataSource->Database;
		return parent::Fill( $dataSource );
	}

	function Update( DataSource $dataSource, DataSource $maskSource = null )
	{
            if( $maskSource == null )
            {
                    // TODO: FAULT TOLERANCE
                    // TODO: ATTEMPT TO GET CURRENT SCHEMATIC AND SWITCH PASSED DATASOURCE ARGUMENT OUT FOR IT, MOVING DATASOURCE TO MASKSOURCE
            }
            if( $dataSource instanceof SQLDataSource
                    && $maskSource !== null )
            {

                $schemaMapper = new SqlSchemaMapper();

                $foundMasks = array();
                //TODO: BIND DATASOURCE RAW DATA KEY TO PARTICULAR PROPERTY OF VALUE (LIKE SQLENTITY OBJECT)
                foreach( $maskSource as $mask )
                    $masks[ $mask->InnerName ] = $mask;

                $transaction = new QueryTransaction();

                foreach( $dataSource as $table )
                {
                    unset( $mask );
                    if( array_key_exists( $table->InnerName , $masks))
                        $mask = $masks[ $table->InnerName ];
                    elseif( array_key_exists( $table->OuterName, $masks ))
                        $mask = $masks[ $table->OuterName ];

                    if( $mask instanceof SQLEntity )
                    {
                        $dataSource[ $table->InnerName ] = $schemaMapper->map( $table, $mask );
                        $foundMasks[ $mask->InnerName ] = $mask;
                    }

                    if( $schemaMapper->hasChanges() )
                    {


                        foreach( $schemaMapper->Changes as $changes )
                        {
                            foreach( $changes as $change => $attr )
                            {
                            switch( $change )
                            {
                                case SqlSchemaMapper::ADD:
                                    // ADD QUERY TO TRANSACTION
                                    $transaction->addQuery(
                                            Query::build()
                                                     ->alter( $foundMasks[ $table->InnerName ] )
                                                     ->add( $attr )
                                            );
                                    break;
                                case SqlSchemaMapper::DROP:
                                    $transaction->addQuery(
                                            Query::build()
                                                     ->alter( $foundMasks[ $table->InnerName ] )
                                                     ->drop( $attr )
                                            );
                                    break;
                                case SqlSchemaMapper::CHANGE:
                                    $transaction->addQuery(
                                            Query::build()
                                                     ->alter( $foundMasks[ $table->InnerName ] )
                                                     ->change( $attr[0], $attr[1] ) // ATTR OLD NAME, NEW ATTR
                                            );
                                    break;
                                case SqlSchemaMapper::JOINTABLE:
                                    if( $attr instanceof SQLEntity )
                                    {
                                        $transaction->addQuery(
                                                Query::build()->create( $attr )
                                                );
                                        $name = $attr->InnerName;

                                        \Core::getInstance()->getDatabase()->getDataSet()->addSchematic( $attr );

                                        //EntityManager::getInstance()->addEntity( $attr );
                                    }
                                    elseif( is_string( $attr ))
                                        $name = $attr;
                                    $masks[ $name ] = $attr;
                                    $foundMasks[ $name ] = $attr;

                                    break;
                                }
                                }
                            }

                        }
                    }

                    $createMasks = array_diff_key( $masks, $foundMasks );
                    // TODO: CREATE TABLE FOREACH
                    foreach( $createMasks as $mask )
                        if( $mask !== null )
                            $transaction->addQuery(Query::build());

                    $dropTables = array_diff_key( $dataSource->Data, $masks );
                    foreach( $dropTables as $table )
                        if( $table !== null )
                            $transaction->addQuery(Query::build());
                    // TODO: DROP TABLE FOREACH
                    if( $transaction->isPrepared() )
                        $transaction->execute( $dataSource->Database );
                    // EXECUTE ANY PENDING CHANGES TO TRANSACTION

            }
            else
            {

            }
            // TODO: CREATE ALTER-COLUMNS AND CREATE-TABLE QUERIES
            // WHERE CHANGES EXIST OR NEW ENTITY EXISTS TO BE ADDED TO DATABASE
	}

	function getSelectCommand()
	{
		$this->Command = Query::build( Query::SQL )
							  ->entities( $this->Database, true );

		return $this->Command;
	}

	function getUpdateCommand()
	{
		//$this->Command = Query::build( Query::SQL )
			//				->update( )
	}
}