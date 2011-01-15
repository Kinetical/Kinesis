<?php
namespace \DBAL\SQL\Data\Entity\Object;

class Mapper extends \DBAL\Data\Entity\Object\Mapper
{

	function __construct(EntityObject $entity)
	{
		$this->setState( ClassMapper::Premapping );

		parent::__construct( $entity );
	}
	function map( Object $item )
	{
		$test = $this;
		//var_dump( $item );
		$data = $item->Data;
		$relations = array_intersect_key( $data, $this->Entity->Relations );

		foreach( $relation as $relation )
		{
			if( $this->Entity->Relations[ $relation ]->isLocalReference()
				&& $data[ $relation ] instanceof Object  )
			{
				$sqlAdapter = new SqlDataAdapter( $this->Entity );
				$sqlAdapter->Update( $data[ $relation ] );
			}
		}

		foreach( $this->Entity->Attributes as $attr )
		{

			if( array_key_exists( $attr->InnerName, $data ) )
			{

				if( $this->Entity->hasRelation( $attr->InnerName ))
				{
					$value = $data[ $attr->InnerName ];
					if( $value instanceof Object
						&& $value->Type->isPersisted()
						&& $value->Type->isPersistedBy('EntityObject')
						&& $value->Type->getPersistenceObject()->PrimaryKey !== null )
						{

							$primaryKey = $value->Type->getPersistenceObject()->PrimaryKey->OuterName;
							if( $value->$primaryKey !== null )
								$value = $value->$primaryKey;
						}

					$data[ $attr->InnerName ] = $value;
				}
			}

			if( $attr->DataType instanceof ObjectType )
			{
				if( !is_string( $data[ $attr->InnerName ] ))
					$data[ $attr->InnerName ] = serialize( $data[ $attr->InnerName ]);
			}

			if( $attr->DataType instanceof IntegerType )
			{
				if( $data[ $attr->InnerName ] == null )
					$data[ $attr->InnerName ] = 0;
			}
		}

		$item->Data = $data;

		return $item;
	}
}