<?php
namespace DBAL\Query;

class Clause extends Parameter
{
	private $_value;
	private $_operator;
	private $_bitwise;
        private $_variable;

	function __construct( $variable, $value, $operator = '=', $bitwise = 'and' )
	{
            if( $variable instanceof \DBAL\Data\Model\Attribute )
            {
                $this->setVariable( $variable );
                $variable = $variable->getName();
            }
            parent::__construct( $variable );

		if( $value instanceof \Core\Object
			&& $value->Type->isPersisted()
			&& $value->Type->isPersistedBy('EntityObject')
			&& ($primaryKey = $value->Type->getPersistenceObject()->getPrimaryKey()) !== null )
			{
				$value = $value->{$primaryKey->getOuterName()};
			}
		$this->_value = $value;
		$this->_operator = $operator;
		$this->_bitwise = $bitwise;
	}

	function getVariable()
	{
            return $this->_variable;
	}

        function setVariable( $variable )
        {
            $this->_variable = $variable;
        }

	function getValue()
	{
		return $this->_value;
	}

	function getOperator()
	{
		return $this->_operator;
	}

	function getBitwiseOperator()
	{
		return $this->_bitwise;
	}
}