<?php 
namespace DBAL\Data;

class Mapper extends \Core\Filter
{
	private $_mapping;
        private $_cache; //TODO: implement this

        const Premapping = 3;
        const Premapped = 4;

        const Mapping = 1;
        const Completed = 2;

	function __construct( array $mapping )
	{
		$this->_mapping = $mapping;

                parent::__construct();
	}
	
	protected function setMapping( array $mapping )
	{
		$this->_mapping = $mapping;
	}
	
	function getMapping()
	{
		return $this->_mapping;
	}

        function hasMapping()
        {
            return ( $this->_mapping !== null ) ? true : false;
        }
	
	function isMapped( $name )
	{	
		if( is_string( $name )
			&& ( array_key_exists( $name, $this->_mapping ))
			||   array_key_exists( $this->matchMapping( $name ), $this->_mapping ) ) 
			return true;
			

			
		return false;
	}
	
	protected function matchMapping( $name )
	{		
		if( is_array( $this->_mapping ))
		{
                    $keys = array_walk( $this->_mapping, 'preg_quote');

			$matches = array();
			$build = str_replace( 
						array('\*','\?'), 
						array('(.*?)','[0-9]'),
						$keys );
						
			foreach( $build as $mapping => $regex )
				if(  strpos($regex, '*')
                                     && preg_match('/^'.$regex.'$/', $name, $matches[] ) > 0 )
					return $mapping;
		}
		
		return false;
	}
	
	protected function map( $value )
	{
		if( is_array( $value ))
			foreach( $value as $key => $item )
				$value[ $key ] = $this->mapData( $item );
		else 
			$value = $this->mapData( $value );
			
		return $value;
	}
	
	protected function mapData( $value )
	{
		if( $this->isMapped( $value ))
			if( array_key_exists( $value, $this->_mapping ))
				return $this->_mapping[ $value ];
			elseif( ($match = $this->matchMapping( $value )) !== false )
				return $this->_mapping[ $match ];
		
		return $value;
	}

         function add( $subject, $object = null )
        {
             if( $subject instanceof \Core\Object )
                 $subject = $subject->Oid;
            if( $object == null )
                    $object = $subject;

            parent::add( $subject, $object );
        }
}