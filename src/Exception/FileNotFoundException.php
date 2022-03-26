<?php

namespace BetterAltoRouter\Exception;

use Exception;

class FileNotFoundException extends Exception
{

    public function __construct($file)
    {
        parent::__construct("The file \"$file\" was not found!");
    }

}