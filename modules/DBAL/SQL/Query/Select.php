<?php
namespace DBAL\SQL\Query;

class Select extends \DBAL\Query\Node
{

	function create( $variables )
	{
		if( !is_array( $variables ))
			$variables = array($variables);

		if( count( $variables ) == 0 )
			$variables[] = '*';
		$this['variables'] = $variables;

		return parent::create();
	}


	function open()
	{
		$sql  = "SELECT \n";

		$c = 0;

                $model = $this->Owner();

		foreach( $this['variables'] as $var )
		{

			if( $model instanceof \DBAL\Data\Model\Attribute
                            && $model->hasAttribute( $var ))
				$sql.=$model->Alias.'.'.$var;
			else
				$sql .= $var;

			if( !count( $this['variables'] == $c )
				&& $c > 0 )
				$sql.=',';
			$c++;
		}

		$sql .= "\n";

		return $sql;
	}
}