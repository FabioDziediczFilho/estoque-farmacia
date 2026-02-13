<?php

namespace App\Observers;

use App\Services\AuditService;
use Illuminate\Database\Eloquent\Model;

class AuditObserver
{
    /**
     * Handle the Model "created" event.
     */
    public function created(Model $model): void
    {
        $name = $model->numero_lote ?? $model->nome ?? $model->name ?? $model->protocolo ?? "ID #{$model->id}";
        $type = strtolower(class_basename($model));

        AuditService::log(
            'created',
            "Criou o {$type}: {$name}",
            $model,
            ['new' => $model->toArray()]
        );
    }

    /**
     * Handle the Model "updated" event.
     */
    public function updated(Model $model): void
    {
        $name = $model->numero_lote ?? $model->nome ?? $model->name ?? $model->protocolo ?? "ID #{$model->id}";
        $type = strtolower(class_basename($model));

        // Pegar apenas o que mudou
        $changes = $model->getChanges();
        $original = [];

        foreach ($changes as $key => $value) {
            if ($key === 'updated_at')
                continue;
            $original[$key] = $model->getOriginal($key);
        }

        // Se nada relevante mudou, não logar
        if (empty($original))
            return;

        AuditService::log(
            'updated',
            "Editou o {$type}: {$name}",
            $model,
            [
                'old' => $original,
                'new' => $changes
            ]
        );
    }

    /**
     * Handle the Model "deleted" event.
     */
    public function deleted(Model $model): void
    {
        $name = $model->numero_lote ?? $model->nome ?? $model->name ?? $model->protocolo ?? "ID #{$model->id}";
        $type = strtolower(class_basename($model));

        AuditService::log(
            'deleted',
            "Removeu o {$type}: {$name}",
            $model,
            ['old' => $model->toArray()]
        );
    }
}
