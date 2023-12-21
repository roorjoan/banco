<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Throwable;

class AccountNotFoundException extends Exception
{
    public function __construct(
        string $message = 'Account not found',
        int $code = 404,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function render(Request $request)
    {
        return response()->json(['message' => $this->message,], $this->code);
    }
}
