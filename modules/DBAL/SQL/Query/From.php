<?php
namespace DBAL\SQL\Query;

class From extends \DBAL\Query\Model\Node
{
	private $_alias;

	function hasAlias()
	{
		return ( is_string($this->_alias) ) ? true : false;
	}

	function getAlias()
	{
		return $this->_alias;
	}

	function setAlias( $alias )
	{
		$this->_alias = $alias;
	}

	function create( $data )
	{
            $create = parent::create( $data );

            if( is_array( $data )
                    && (!array_key_exists( 1, $data )
                    || $data[1] == true ))
                    $this->Alias = $this->Model->Alias;
            elseif( !is_array( $data ))
                    $this->Alias = $this->Model->Alias;

            return $create;
	}

	function open()
	{
		$sql  = "FROM \n";

                $model = $this->getModel();

                if( $model instanceof \DBAL\Data\Model )
                {
                    $sql .= $model->InnerName;
                    if( $this->hasAlias() )
                             $sql .= ' AS ' . $this->Alias;
                }
                else
                {
                    $sql .= $this['table'];
                }    

		$sql .= "\n";

		return $sql;
	}
}