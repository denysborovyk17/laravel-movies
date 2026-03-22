<?php

namespace App\Exceptions;

use Exception;

class MovieNotFoundException extends Exception
{
    protected int $movieId;

    public function __construct(int $movieId, ?string $message = null)
    {
        $this->movieId = $movieId;
        $message = "Movie with ID {$movieId} not found";

        parent::__construct($message);
    }
}
