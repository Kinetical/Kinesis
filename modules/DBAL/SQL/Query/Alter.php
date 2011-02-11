<?php
namespace DBAL\SQL\Query;

class Alter extends \DBAL\Query\Node\Container
{
	function create( $data )
	{
		$this->Resource->Stream = new \ORM\SQLStream( Stream::WRITE );
		return parent::create( $data );
	}

	function open()
	{
		return 'ALTER TABLE '. $this->Entity->InnerName."\n";
	}
}
