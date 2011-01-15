<?php
namespace DBAL\SQL\Data\Type;

class Currency extends \DBAL\Data\Type\Currency implements \DBAL\SQL\Data\Type
{
	function getDefaultLength()
	{
		return '10,2';
	}
}
