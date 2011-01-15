<?php
namespace DBAL\SQL\Data\Type;

class Object extends \DBAL\Data\Type\Object implements \DBAL\SQL\Data\Type
{
	function getDefaultLength()
	{
		return null;
	}

	function toBase()
	{
		return 'text';
	}
}
