<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search != '') {
            $query->where('model_type', 'like', '%' . $request->search . '%')
                ->orWhere('action', 'like', '%' . $request->search . '%');
        }

        $logs = $query->paginate(20);
        $logs->appends($request->all());

        return view('admin.audit-logs.index', compact('logs'));
    }
}
