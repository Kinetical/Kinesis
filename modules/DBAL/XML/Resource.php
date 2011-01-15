<?php
namespace DBAL\XML;

class Resource extends \IO\File\Resource implements \RecursiveIterator
{

	public function getChildren()
	{
		return $this->current()->getChildren();
	}

	public function hasChildren()
	{
		return $this->current()->hasChildren();
	}

	function flush()
	{
		return $this->getLoader()->flush();
	}

        function valid()
        {
            // XML QUERIES ARE PRE-BUILT IN QUERYNODES
            if( !is_bool($this->getQuery()->getResults()) )
            {
                return true;
            }

            return false;
        }


	function getDefaultHydrator()
	{
		return new Hydrator\DocumentHydrator( $this );
	}
}