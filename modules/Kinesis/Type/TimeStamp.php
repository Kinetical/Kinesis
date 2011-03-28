<?php
namespace Kinesis\Type;

class TimeStamp extends String
{      
    function getDefaultLength()
    {
        return 14;
    }
    
    function getDefaultValue()
    {
        return 'CURRENT_TIMESTAMP';
    }
}
