<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    /**
     * Exibir a listagem de logs de auditoria.
     */
    public function index()
    {
        $logs = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.audits.index', compact('logs'));
    }
}
