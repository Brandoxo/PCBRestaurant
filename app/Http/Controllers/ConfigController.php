<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shifts;
use App\Models\CashFloats;
use App\Models\ConfigRoomService;

class ConfigController extends Controller
{
    public function index()
    {
        $shifts = Shifts::all();
        $cashFloats = CashFloats::all();
        $roomServiceConfig = ConfigRoomService::select('service_cost', 'is_active')->get();

        return inertia('Config/Index', [
            'shifts' => $shifts,
            'cashFloats' => $cashFloats,
            'roomServiceConfig' => $roomServiceConfig,
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

    public function updateRoomService(Request $request)
    {
        $isActive = $request->input('is_active');
        $serviceCost = $request->input('service_cost');

        $config = ConfigRoomService::first();

        if ($config) {
            $config->is_active = $isActive;
            $config->service_cost = $serviceCost;
            $config->save();
        } else {
            ConfigRoomService::create([
                'is_active' => $isActive,
                'service_cost' => $serviceCost,
            ]);
        }

        return redirect()->back()->with('success', 'Configuración de servicio de habitaciones actualizada correctamente.');
    }
}