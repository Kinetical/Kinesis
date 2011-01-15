<?php
namespace DBAL\Data\Entity\Relationship\Loader;

class Immediate extends \DBAL\Data\Entity\Relationship\Loader
{
	function load( $path, $args = null )
	{
		$entity = $this->Source->Type->getPersistenceObject();

		if( $entity instanceof EntityObject )
			foreach( $entity->Relations as $relation )
			{
				if( $relation->isLocalReference() )
				{
					$loader = new DeferredLoader( $this->Source, $relation );
					$this->Source->{$relation->Name} = $loader->load( null );
				}
			}

		return $this->Source;
	}
}