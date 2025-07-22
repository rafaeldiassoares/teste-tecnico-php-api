<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public function __construct($message = null, $code = 404)
    {
        parent::__construct($message, $code);
    }

    public function render($request)
    {
        return response()->json([
            'status' => 404,
            'message' => $this->getMessage(),
        ], 404);
    }
}
