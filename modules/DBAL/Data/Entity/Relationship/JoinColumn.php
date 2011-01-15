<?php
namespace DBAL\Data\Entity\Relationship;

class JoinColumn extends \DBAL\Data\Entity\Relationship
{
	private $_referencedAttribute;
	private $_unique = false;

	function __construct( $name = null, $referencedAttribute = null )
	{
		if( $name instanceof EntityObject )
			$name = $name->PrimaryKey;

		if( $name instanceof EntityAttribute )
			$name = $name->InnerName;

		if( is_string( $name ))
			$this->Name = $name;

		if( $referencedAttribute instanceof EntityObject )
			$referencedAttribute = $referencedAttribute->PrimaryKey;

		if( $referencedAttribute instanceof EntityAttribute )
			$referencedAttribute = $referencedAttribute->InnerName;

		if( is_string( $referencedAttribute ))
			$this->ReferencedAttribute = $referencedAttribute;
	}

	function getReferencedAttribute()
	{
		return $this->_referencedAttribute;
	}

	function setReferencedAttribute( $attribute )
	{
		$this->_referencedAttribute = $attribute;
	}

	function getQuery( RelationshipLoader $loader )
	{
            if( $loader instanceof DeferredLoader )
            {
                if( $loader->Relation->inversedBy !== null )
                        $keyColumn = $loader->Relation->inversedBy;
                else
                        $keyColumn = $loader->Relation->Entity->PrimaryKey->InnerName;
                $query = Query::build( Query::SQL, Query::HYDRATE_SCALAR )
                                  ->select()
                                  ->from( $loader->Relation->Entity )
                                  ->where( $keyColumn ,
                                                   $loader->Source->Data[$this->Name] );

                $query->DataType = $loader->Relation->Entity->InnerName;

                return $query;
            }
            elseif ( $loader instanceof ImmediateLoader )
            {
                    // JOIN
            }

            return null;
	}
}