<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetCompanyContextMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->company_id) {
            $company = auth()->user()->company;
            $settings = Cache::remember("company_{$company->id}_settings", 3600, function () use ($company) {
                return $company->settings()->pluck('value', 'key')->toArray();
            });

            View::share('company', $company);
            View::share('companySettings', $settings);

            if (isset($settings['timezone'])) {
                config(['app.timezone' => $settings['timezone']]);
                date_default_timezone_set($settings['timezone']);
            }

            if (isset($settings['language'])) {
                app()->setLocale($settings['language']);
            }

            View::share('darkMode', $company->dark_mode ?? false);
        }

        return $next($request);
    }
}
