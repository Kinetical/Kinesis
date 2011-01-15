<?php
namespace IO\Context\Parameter;

class Collection extends \IO\Context\Collection
{

    function __set( $key, $value )
    {
        $param = array( $key => $value );
        stream_context_set_params($this->getContext()->getResource(), $param);

        parent::__set( $key, $value );
    }
}
