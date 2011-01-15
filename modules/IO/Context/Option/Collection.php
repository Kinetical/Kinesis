<?php
namespace IO\Context\Option;

class Collection extends \IO\Context\Collection
{
    function __set( $key, $value )
    {
        $options = array( $key => $value );
        stream_context_set_option( $this->getContext->getResource(), $options );

        parent::__set( $key, $value );
    }
}
