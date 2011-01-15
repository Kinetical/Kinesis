<?php
namespace DBAL\Data;

class Context extends \Core\Object
{
	const Deferred = 1;
	const Immediate = 2;

	private $_loading = Context::Deferred;


	function getLoading()
	{
		return $this->_loading;
	}

	function setLoading( $loading )
	{
		$this->_loading = $loading;
	}
}