<?php
namespace DBAL\XML\Query;

class ns extends \DBAL\Query\Node
{
	function open()
	{

	}

	function create($data)
	{
		$this['namespace'] = $data;
		return parent::create();
	}
}