<?php
namespace SQL\Control;

class Mapper extends EntityObjectMapper
{
    function map(\Web\UI\Control $control )
    {
        $controlType = $control->Type->getPersistenceObject()->BaseSchematic;

        if( $controlType instanceof \Web\UI\ControlType )
        {
            $props = array();
            $attributes = $control->getAttributes();

            foreach( $controlType->getAttributes() as $attr )
            {
                $innerName = $attr->getInnerName();
                    if( array_key_exists( $innerName, $attributes ))
                        $props[ $innerName ] = $attributes[ $innerName ];
            }

            if( count( $props ) > 0 )
                $control->properties = $props;
        }

        return parent::map( $control );
    }
}