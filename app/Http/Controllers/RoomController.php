<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rooms;
class RoomController extends Controller
{
    public function index(){
        $rooms = Rooms::all();
        return inertia('Rooms/Index', ['rooms' => $rooms]);
    }

    public function create(){
        $rooms = Rooms::all();
        return inertia('Rooms/Create', ['rooms' => $rooms]);
    }
    
    public function store(Request $request){

        $room = new Rooms();
        $room->prefix = strtoupper($request->prefix);
        $room->number = $request->number;
        $room->name = $request->name;
        $room->status = $request->status;
        $room->capacity = $request->capacity;
        $room->save();
        return redirect()->route('Rooms/Index');
    }

    public function destroy($id)
    {
        $room = Rooms::findOrFail($id);
        $room->delete();
        return redirect()->route('Rooms/Index')
        ->with('success', 'Rooms has been deleted.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'prefix' => 'required|string',
            'number' => 'required|integer',
            'status' => 'required',
            'capacity' => 'required|integer'
        ]);
        $rooms = Rooms::findOrFail($id);
        $rooms->update($request->all());
            return redirect()->route('Rooms/Index')
            ->with('Succes', 'Room has been update succesfully');
    }
}
