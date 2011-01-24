<?php
namespace IO\File\Stream;

class Mode extends \IO\Stream\Mode
{
    const ReadWrite = 'r+';
    const WriteRead = 'w+';
    const Append = 'a';
    const AppendRead = 'a+';
    const NewExclusive = 'x';
    const NewRead = 'x+';
    const CreateWrite = 'c';
    const CreateRead = 'c+';
}