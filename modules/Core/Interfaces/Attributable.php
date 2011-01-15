<?php
namespace Core\Interfaces;

interface Attributable
{
    function getAttributes();
    function setAttributes( array $attibutes );
    function hasAttribute( $name );    
}
