<?php
namespace DBAL\SQL\Query;

class Drop extends \DBAL\Query\Node
{
	//DROP  `test`
	function create( $data )
	{
		if( is_string( $data ))
			$this['attribute'] = $data;
		elseif( $data instanceof EntityAttribute )
		{
			$this->Resource->Stream = new SQLStream( Stream::WRITE );
			$this['attribute'] = $data->InnerName;
			$this->QueryBuilder->Nodes['alter']->addChild( $this );
		}
		elseif( $data instanceof EntityObject )
		{
			$this->Resource->Stream = new SQLStream( Stream::WRITE );
			$this['entity'] = $data->InnerName;
			return parent::create();
		}


		return false;
	}

	function open()
	{

		$sql  = 'DROP ';
		if( isset( $this['entity']))
		{
			//echo '-table: '.$this['entity']."<br/>\n";
			$sql .= 'TABLE `'.$this['entity'].'`';
		}
		elseif( isset( $this['attribute']))
		{
			//echo '-column: '.$this['attribute']."<br/>\n";
			$sql .= '`'.$this['attribute'].'`';
		}
		//else
			//throw new InvalidArgumentException();

		return $sql;
	}
}