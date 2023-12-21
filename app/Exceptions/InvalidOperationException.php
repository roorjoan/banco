<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Throwable;

class InvalidOperationException extends Exception
{
    public function __construct(
        string $message = 'You cannot withdraw that amount.',
        int $code = 500,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function render(Request $request)
    {
        return response()->json(['message' => $this->message,], $this->code);
    }
}
