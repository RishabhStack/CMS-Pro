<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCompanyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->company_id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No company assigned to your account.'
                ], 403);
            }
            return redirect()->route('login')
                ->with('error', 'No company assigned to your account.');
        }

        if (auth()->check() && auth()->user()->company) {
            $company = auth()->user()->company;
            if ($company->status !== 'active') {
                auth()->logout();
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Your company account is inactive.'
                    ], 403);
                }
                return redirect()->route('login')
                    ->with('error', 'Your company account is inactive.');
            }
        }

        return $next($request);
    }
}
