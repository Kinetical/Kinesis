<?php
namespace DBAL\XML\Query;

class Attribute extends \DBAL\Query\Node
{
    function create( $data )
    {
        $this['attribName'] = $data[0];
        $this['attribValue'] = $data[1];

        if( count( $data ) == 3
            && is_bool($data[2] ))
            $this['equals'] = $data[2];
        else
            $this['equals'] = true;

        $this->getQueryBuilder()->Nodes['where']->addChild( $this );

        return false;
    }

    function open()
    {
         $newPath= "@".$this['attribName']."='".$this['attribValue']."'";
         if( !$this['equals'] )
             $newPath = 'not('.$newPath.')';
         
         $this->getQueryBuilder()->Nodes['where']['xpath'] .= $newPath;
    }
}