<?php
namespace DBAL\SQL\Data\Type;

class Integer extends \DBAL\Data\Type\Integer implements \DBAL\SQL\Data\Type
{
	function getDefaultLength()
	{
		return '10';
	}

	function toBase()
	{
		return 'int';
	}
}
