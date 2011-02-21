<?php
namespace Kinesis\Task;

abstract class Statement extends Node
{
    function getQuery()
    {
        return $this->Parent->Parameters['Query'];
    }
}