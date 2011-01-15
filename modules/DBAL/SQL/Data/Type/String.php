<?php
namespace DBAL\SQL\Data\Type;

class String extends \DBAL\Data\Type\String implements \DBAL\SQL\Data\Type
{
	function getDefaultLength()
	{
		return 50;
	}

	function toBase()
	{
		return 'varchar';
	}
}