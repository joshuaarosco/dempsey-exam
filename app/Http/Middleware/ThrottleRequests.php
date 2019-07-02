<?php

namespace App\Http\Middleware;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Middleware\ThrottleRequests as BaseThrottleRequest;

class ThrottleRequests extends BaseThrottleRequest
{
	/**
     * Create a 'too many attempts' response.
     *
     * @param  string  $key
     * @param  int  $maxAttempts
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function buildResponse($key, $maxAttempts)
    {
        $retryAfter = $this->limiter->availableIn($key);

        $response = new JsonResponse([
            'msg' => "Too many attempts. Try again after {$retryAfter} seconds.",
            'status' => FALSE,
            'status_code' => "TOO_MANY_ATTEMPTS",
        ], 429);

        return $this->addHeaders(
            $response, $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
            $retryAfter
        );
    }
}
