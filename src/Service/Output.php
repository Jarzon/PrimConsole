<?php
namespace Prim\Console\Service;

class Output
{
    public function writeLine(string $output)
    {
        echo "$output\n";
    }
}