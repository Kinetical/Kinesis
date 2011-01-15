<?php
namespace DBAL\SQL\Query;

class Where extends \DBAL\Query\Node
{
    function create( $params )
    {
            // VAR, VALUE, OP, BITWISE
        if( !isset( $params[2]))
                $params[2] = '=';

        if( !isset( $params[3]))
                $params[3] = 'AND';

        $parameters = $this->getQueryBuilder()
                           ->getParameters();

        $name = $params[0];
        $clause = new \DBAL\Query\Clause( $name, $params[1], $params[2], $params[3]);

        if( !isset( $parameters->where ))
            $parameters->where = new \Core\Collection();

        if( !isset($parameters->where->$name))
             $parameters->where->$name = new \Core\Collection();

        $parameters->where[ $name ][] = $clause;

        if( !($builder->Nodes['where'] instanceof Where ) )
                return parent::create();
    }


    function open()
    {
        $sql  = "WHERE \n";

        $builder =  $this->getQueryBuilder();
        $parameters = $builder->getParameters();
        $c = 0;
        foreach( $parameters->where as $param )
        {            

            foreach( $param as $name => $clause )
            {
                if( $c > 0 )
                $sql .= ' '.strtoupper( $clause->getBitwiseOperator() ).' ';

                $sql .= $name;

                $sql .= $clause->getOperator();

                $sql .= $clause->getValue();
            }

            $c++;
        }

//        foreach( $parameters as $clauses )
//        {
//            $c = count( $clauses );
//            $open = false;
//            if( $c > 1 )
//            {
//                $sql .= ' '.strtoupper( $clause->BitwiseOperator ).' ';
//                $sql .= ' (';
//                $open = true;
//            }
//            $ccount = 0;
//            var_dump( $clauses );
//            foreach( $clauses as $clause )
//            {
//                var_dump( $clause );
//                //if( ($c > 1 && $count > 1) || ( !$open && $count != 0))
//                if( $open )
//                {
//                        if( $ccount > 0 )
//                        {
//                                $sql .= ' '.strtoupper( $clause->BitwiseOperator ).' ';
//                        }
//                }
//                else
//                {
//                if( $count != 0  )
//                {
//                        $sql .= ' '.strtoupper( $clause->BitwiseOperator ).' ';
//                }
//                }
//
//                if( $clause->Variable instanceof \DBAL\Data\Model\Attribute )
//                {
//                        $sql .= $clause->Variable->Entity->Alias.'.';
//                        $sql .= ''.$clause->Variable->InnerName.'';
//                }
//                else
//                {
//                        $sql .= $this->EntityNode->Entity->Alias.'.';
//                        $sql .= ''.$clause->Name.'';
//                }
//
//                $sql .= ' '.strtoupper($clause->Operator).' ';
//                $sql .= "'". $clause->Value . "'";
//
//
//                $count++;$ccount++;
//            }
//            if( $c > 1 )
//            {
//                $sql .= ') ';
//            }
//        }
        $sql .= "\n";

        return $sql;
    }
}