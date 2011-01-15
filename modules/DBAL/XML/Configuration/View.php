<?php
namespace DBAL\XML\Configuration;

class View extends \DBAL\Data\View
{
    private $_directiveAttributes;
    private $_directiveName;

    function __construct( $directiveName, $dirAttrs = null )
    {
        parent::__construct();
        $this->setName( $directiveName );
        
        if( $dirAttrs !== null
            && !is_array( $dirAttrs ) )
            $dirAttrs = array( $dirAttrs );

        if( is_array( $dirAttrs ))
            $this->setAttributes( $dirAttrs );
    }
    function getName()
    {
        return $this->_directiveName;
    }
    function setName( $name )
    {
        $this->_directiveName = $name;
    }
    function getAttributes()
    {
        return $this->_directiveAttributes;
    }

    function setAttributes( array $names )
    {
        $this->_directiveAttributes = $names;
    }
    function prepare()
    {
        //var_dump( $this->Adapter->Resource );
        $command = $this->setCommand (\ORM\Query::build( \ORM\Query::XML )
                                      ->from( $this->getAdapter()->getResource() )
                                      ->where( $this->getName() ));

        $attributes = $this->getAttributes();

        foreach( $attributes as $attr )
            if( is_array( $attr ))
                $command->orAttribute( $attr[0], $attr[1] );
            else
                $command->orAttribute( 'name', $attr );

         parent::prepare();
    }
}
