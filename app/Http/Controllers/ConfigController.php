<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shifts;
use App\Models\CashFloats;

class ConfigController extends Controller
{
    public function index()
    {
        $shifts = Shifts::all();
        $cashFloats = CashFloats::all();
        return inertia('Config/Index', [
            'shifts' => $shifts,
            'cashFloats' => $cashFloats
        ]);
    }

    public function updateShift(Request $request)
    {
        $shift = Shifts::where('name_shift', $request->input('name_shift'))->first();

        if ($shift) {
            $shift->start_time = $request->input('start_time');
            $shift->end_time = $request->input('end_time');
            $shift->save();
        }

        return redirect()->back()->with('success', 'Horario del turno actualizado correctamente.');
    }
}