<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            if (!Auth::check()) return;

            $data = $model->toArray();
            $data = array_diff_key($data, array_flip(static::$auditIgnore ?? ['id', 'company_id', 'created_at', 'updated_at', 'deleted_at']));
            $model->logAuditEvent('created', null, $data);
        });

        static::updated(function ($model) {
            if (!Auth::check()) return;

            $old = [];
            $new = [];
            $ignore = $model::$auditIgnore ?? ['id', 'company_id', 'created_at', 'updated_at', 'deleted_at'];
            foreach ($model->getDirty() as $key => $value) {
                if (in_array($key, $ignore)) continue;
                $old[$key] = $model->getOriginal($key);
                $new[$key] = $value;
            }

            if (!empty($new)) {
                $model->logAuditEvent('updated', $old, $new);
            }
        });

        static::deleted(function ($model) {
            if (!Auth::check()) return;

            $data = $model->toArray();
            $data = array_diff_key($data, array_flip(static::$auditIgnore ?? ['id', 'company_id', 'created_at', 'updated_at', 'deleted_at']));
            $model->logAuditEvent('deleted', $data, null);
        });
    }

    protected function logAuditEvent(string $event, ?array $oldValues, ?array $newValues): void
    {
        $sensitiveKeys = ['password', 'password_confirmation', 'remember_token', 'api_token', 'secret', 'token', 'preshared_key'];

        $filterOld = $oldValues ? $this->filterSensitive($oldValues, $sensitiveKeys) : null;
        $filterNew = $newValues ? $this->filterSensitive($newValues, $sensitiveKeys) : null;

        try {
            \App\Models\AuditLog::create([
                'company_id' => Auth::user()->company_id ?? $this->company_id ?? null,
                'user_id' => Auth::id(),
                'event' => $event,
                'auditable_type' => get_class($this),
                'auditable_id' => $this->id ?? $this->getKey(),
                'old_values' => $filterOld ? json_encode($filterOld) : null,
                'new_values' => $filterNew ? json_encode($filterNew) : null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_by' => Auth::id(),
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Audit log failed: ' . $e->getMessage());
        }
    }

    private function filterSensitive(array $data, array $keys): array
    {
        foreach ($keys as $key) {
            if (isset($data[$key])) {
                $data[$key] = '[REDACTED]';
            }
        }
        return $data;
    }
}
