<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Registrar uma atividade no sistema.
     *
     * @param string $action Tipo da ação (created, updated, deleted, login, etc)
     * @param string $description Descrição amigável do que ocorreu
     * @param object|null $model Opcional, o objeto Eloquent afetado
     * @param array|null $properties Opcional, dados extras (ex: antes e depois)
     */
    public static function log($action, $description, $model = null, $properties = null)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'properties' => $properties,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
