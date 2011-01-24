<?php
namespace Util\Interfaces;

interface Attributable
{
    function getAttributes();
    function setAttributes( array $attibutes );
    function hasAttribute( $name );    
}
