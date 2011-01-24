<?php
namespace Util\Interfaces;

interface Node
{
    function getChildren();
    function getParent();
    function isRoot();
    function setParent( $parent );
    function setChildren( array $children );
}
