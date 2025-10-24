<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\CashFloats;

class CashFloatController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric',
            'user_id' => 'nullable|exists:users,id',
            'type' => 'required|in:open,adjust,close',
            'notes' => 'nullable|string',

        ]);
        $data['user_id'] = $request->user()->id ?? null;
        $cashFloat = CashFloats::create($data);
        return response()->json($cashFloat, 201);
    }

        public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric',
            'user_id' => 'nullable|exists:users,id',
            'type' => 'required|in:open,adjust,close',
            'notes' => 'nullable|string',
        ]);

        $data['user_id'] = $request->user()->id ?? null;

        $cashFloat = CashFloats::create($data);

        return response()->json($cashFloat, 201);
    }
};
