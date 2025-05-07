<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CouldNotGenerateUniqueKeyException extends \Exception
{
    /** @phpstan-ignore missingType.property */
    protected $message = 'Sorry, our system could not create a short link for you at this time.
                        Please try again in a few moments.a';

    /**
     * Report the exception.
     */
    public function report(Request $request): void
    {
        Log::error($this->getMessage(), [
            'exception' => $this,
            'url'       => $request->fullUrl(),
            'ip'        => $request->ip(),
        ]);
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request): Response
    {
        return response()->view('errors.key_generation_failed', [
            'message' => $this->getMessage(),
        ], 503);
    }
}
