<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CashAuditController extends Controller
{

    public function index()
    {
        $cashAudits = \App\Models\CashAudit::with('user')->orderBy('created_at', 'desc')->get();
        return inertia('CashAudit/Index', [
            'cashAudits' => $cashAudits
        ]);
    }

    public function store (Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'shift' => 'required|in:Matutino,Vespertino',
            'initial_amount' => 'required|numeric|min:0',
            'total_tips' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'final_amount' => 'nullable|numeric|min:0',
        ]);

        $cashAudit = \App\Models\CashAudit::create([
            'user_id' => $request->user_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'shift' => $request->shift,
            'initial_amount' => $request->initial_amount,
            'total_tips' => $request->total_tips,
            'total_amount' => $request->total_amount,
            'final_amount' => $request->final_amount,
        ]);

        return response()->json(['message' => 'Cash audit record created successfully', 'data' => $cashAudit], 201);
    }
}
