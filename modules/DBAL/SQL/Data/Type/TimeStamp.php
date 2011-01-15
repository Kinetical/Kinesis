<?php
namespace DBAL\SQL\Data\Type;

class TimeStamp extends \DBAL\Data\Type\TimeStamp implements \DBAL\SQL\Data\Type
{
	function getDefaultLength()
	{
		return 14;
	}

	function toBase()
	{
		return 'timestamp';
	}
}