<?php

namespace App\Exceptions;

use Exception;

class MissingModel extends Exception
{
    /**
     * Constructor where custom message and code are set.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct('Model is missing.');
    }
}
