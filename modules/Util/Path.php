<?php
namespace Util\Path;

function isAbsolute($path)
{
    if ($path[0] == '/' || $path[0] == '\\' ||
        (strlen($path) > 3 && ctype_alpha($path[0]) &&
         $path[1] == ':' &&
        ($path[2] == '\\' || $path[2] == '/')
          )
        )
    {
        return true;
    }

    return false;
}

function isRelative($path)
{
    //TODO: this
}
