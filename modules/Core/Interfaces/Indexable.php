<?php
namespace Core\Interfaces;

interface Indexable
{
    function getIndex();
    function setIndex( $idx );
    function hasIndex();
}