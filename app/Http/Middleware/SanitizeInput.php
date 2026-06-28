<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInput
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('get')) {
            return $next($request);
        }

        $input = $request->all();
        $sanitized = $this->sanitize($input);
        $request->merge($sanitized);

        return $next($request);
    }

    protected function sanitize(mixed $data): mixed
    {
        if (is_string($data)) {
            return strip_tags($data);
        }

        if (is_array($data)) {
            $sanitized = [];
            foreach ($data as $key => $value) {
                if ($this->shouldSkip($key)) {
                    $sanitized[$key] = $value;
                } else {
                    $sanitized[$key] = $this->sanitize($value);
                }
            }
            return $sanitized;
        }

        return $data;
    }

    protected function shouldSkip(string $key): bool
    {
        return stripos($key, 'password') !== false;
    }
}
