<?php 
namespace Core;

class Util
{
    public static function isPathAbsolute($path)
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
}