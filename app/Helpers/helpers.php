<?php

use App\Models\Company;
use App\Models\CompanySetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('currentCompany')) {
    function currentCompany(): ?Company
    {
        if (auth()->check()) {
            return auth()->user()->company;
        }
        return null;
    }
}

if (!function_exists('company')) {
    function company(): ?Company
    {
        return currentCompany();
    }
}

if (!function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        $company = currentCompany();
        if (!$company) {
            return $default;
        }

        return Cache::remember("company_{$company->id}_setting_{$key}", 3600, function () use ($company, $key, $default) {
            $setting = CompanySetting::where('company_id', $company->id)
                ->where('key', $key)
                ->first();

            return $setting ? $setting->value : $default;
        });
    }
}

if (!function_exists('currency')) {
    function currency($amount): string
    {
        $symbol = setting('currency_symbol', '$');
        $position = setting('currency_position', 'prefix');
        $decimal = setting('decimal_separator', '.');
        $thousand = setting('thousand_separator', ',');

        $formatted = number_format((float) $amount, 2, $decimal, $thousand);

        return $position === 'prefix' ? $symbol . $formatted : $formatted . $symbol;
    }
}

if (!function_exists('dateFormat')) {
    function dateFormat($date): string
    {
        if (!$date) return '';
        $format = setting('date_format', 'Y-m-d');
        return $date instanceof \Carbon\Carbon
            ? $date->format($format)
            : \Carbon\Carbon::parse($date)->format($format);
    }
}

if (!function_exists('timeFormat')) {
    function timeFormat($time): string
    {
        if (!$time) return '';
        $format = setting('time_format', 'H:i:s');
        return $time instanceof \Carbon\Carbon
            ? $time->format($format)
            : \Carbon\Carbon::parse($time)->format($format);
    }
}

if (!function_exists('employeeCode')) {
    function employeeCode(): string
    {
        $company = currentCompany();
        $prefix = setting('employee_code_prefix', 'EMP');
        $length = setting('employee_code_length', 5);
        $lastCode = \App\Models\Employee::where('company_id', $company->id)
            ->withTrashed()
            ->max('employee_code');

        if ($lastCode) {
            $number = (int) substr($lastCode, strlen($prefix)) + 1;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, $length, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('generateReference')) {
    function generateReference(string $prefix = 'REF'): string
    {
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(6));
        return "{$prefix}-{$date}-{$random}";
    }
}

if (!function_exists('responseSuccess')) {
    function responseSuccess(string $message = 'Success', $data = null, $meta = null, int $code = 200): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data ?? [],
        ];

        if ($meta) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $code);
    }
}

if (!function_exists('responseError')) {
    function responseError(string $message = 'Error', $errors = null, int $code = 400): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}

if (!function_exists('uploadFile')) {
    function uploadFile($file, string $path = 'uploads', string $disk = 'public'): string
    {
        return $file->store($path, $disk);
    }
}

if (!function_exists('deleteFile')) {
    function deleteFile(?string $path, string $disk = 'public'): bool
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }
        return false;
    }
}

if (!function_exists('activeMenu')) {
    function activeMenu(string ...$routes): string
    {
        foreach ($routes as $route) {
            if (request()->routeIs($route)) {
                return 'active';
            }
            if (request()->segment(1) === $route) {
                return 'active';
            }
        }
        return '';
    }
}

if (!function_exists('can')) {
    function can(string $permission): bool
    {
        if (!auth()->check()) return false;
        return auth()->user()->can($permission);
    }
}

if (!function_exists('isOwner')) {
    function isOwner(): bool
    {
        if (!auth()->check()) return false;
        return auth()->user()->hasRole('Owner');
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin(): bool
    {
        if (!auth()->check()) return false;
        return auth()->user()->hasRole('Admin');
    }
}

if (!function_exists('avatar')) {
    function avatar(?string $path = null, string $name = 'User'): string
    {
        if ($path) {
            return Storage::url($path);
        }
        $initial = strtoupper(substr($name, 0, 1));
        $colors = ['#4f46e5', '#7c3aed', '#ec4899', '#f59e0b', '#10b981', '#3b82f6', '#ef4444', '#14b8a6'];
        $color = $colors[crc32($name) % count($colors)];

        return "https://ui-avatars.com/api/?name={$initial}&color=fff&background=" . urlencode($color);
    }
}

if (!function_exists('menuOpen')) {
    function menuOpen(string ...$routes): string
    {
        foreach ($routes as $route) {
            if (request()->routeIs($route) || request()->segment(1) === $route) {
                return 'menu-open';
            }
        }
        return '';
    }
}

if (!function_exists('formatBytes')) {
    function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

if (!function_exists('timeAgo')) {
    function timeAgo($date): string
    {
        if (!$date) return '';
        return \Carbon\Carbon::parse($date)->diffForHumans();
    }
}
