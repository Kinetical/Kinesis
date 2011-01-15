<?php
namespace DBAL\SQL\Data\Entity;

class Attribute extends \DBAL\Data\Entity\Attribute
{

	private $_length;

	function equals( SQLAttribute $attr )
	{
		if( (int)$this->getLength() !== (int)$attr->getLength() )
			return false;

		return parent::equals( $attr );
	}

	public function getLength() {
		//if( $this->_length == null )
                       //$this->_length = $this->getDataType()->getDefaultLength();
		return $this->_length;
	}

	public function setLength($length) {
		$this->_length = $length;
	}

	function getInnerName()
	{
            return parent::getInnerName();
	}
}