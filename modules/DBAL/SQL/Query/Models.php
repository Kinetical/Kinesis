<?php
namespace DBAL\SQL\Query;

class Models extends \DBAL\Query\Node
{
	function create( $data )
	{
		if( !is_array( $data ))
			$data[] = $data;
		if( is_array( $data ))
			foreach( $data as $value )
				if( $value instanceof SQLDatabase
					|| $value instanceof SQLConnection )
					$this['database'] = $value;
				elseif( is_bool( $value ))
					$this['clone'] = $value;

		return parent::create( $data );
	}

	function open()
	{
		//TODO: LOAD transaction
		//		POSSIBLY cached depending on core concurrency configuration
		$queryBuilder = Query::build( Query::SQL, Query::HYDRATE_ARRAY )
				       ->tables();

		$tables = $queryBuilder->execute( $this['database'] );
                $dataSet = \Core::getInstance()->getDatabase()->getDataSet();
		foreach( $tables as $table )
		{
			$queryBuilder = Query::build( Query::SQL, Query::HYDRATE_TREE )
						  		  ->columns()->from( $table, false );

			$queryBuilder->getQuery()->getResource()->setClassMapper( new SQLAttributeMapper() );

			$attributes = $queryBuilder->execute( $this['database'] );

			if( $this['clone'] == true )
				$entity = new SQLEntity( $table );
			else
				$entity = $dataSet->Schematics[ $table ];

			$entity->setAttributes( $attributes );

			$entities[ $entity->getInnerName() ] = $entity;
		}

		if( is_array( $entities ))
			$this->getQuery()->setResults ($entities);
	}
}