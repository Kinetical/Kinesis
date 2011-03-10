<?php
namespace DBAL\XML\Query;

class Where extends \Kinesis\Task\Statement
{
    public $XPath;
    
    function __construct($xpath, \Kinesis\Task $parent = null )
    {
        if( is_string( $xpath ))
            $this->XPath = $xpath;
        
        parent::__construct( array(), $parent );
    }
    
    function execute()
    {       
        $children = parent::execute();
        
        $this->XPath .= '['.$children.']';
        
        $this->getComponent()->Parameters['xpath'] = $this->XPath;
        
        return $this->XPath;
    }
}
