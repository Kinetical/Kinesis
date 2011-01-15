<?php
namespace DBAL\SQL\Query;

class Insert extends \DBAL\Query\Model\Node
{
	function create( $data )
	{
            //$this->Resource->Stream = new SQLStream( Stream::WRITE );

            if( is_string( $data ))
                $this['table'] = $table;
            elseif( $data instanceof \DBAL\Data\Model )
                $this->setModel( $data );

            return parent::create( $data );
	}

	function open()
	{
            $model = $this->getModel();

            if( $model !== null )
                $table = $model->getName();
            else
                $table = $this['table'];

		$sql  = "INSERT INTO  \n";
		$sql .= $table;
		$sql .= "\n";

		return $sql;
	}
}