<?php

namespace App\Http\Controllers;
use App\Models\Mesas;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $mesas = Mesas::paginate(30); // O Mesas::all()
        return inertia('Tables/Index', [
            'mesas' => $mesas
        ]);
    }

    public function create()
    {

        $mesas = Mesas::all();
        return inertia('Tables/Create', [
            'mesas' => $mesas
        ]);
    }
    
    public function store(Request $request)
  {
    $request->validate([
      'number' => 'required|integer',
      'status' => 'required',
      'capacity' => 'required|integer',
    ]);
    Mesas::create($request->all());
    return redirect()->route('Tables/Index')
      ->with('success', 'Table created successfully.');
  }


    public function destroy($id)
    {
        $mesa = Mesas::findOrFail($id);
        $mesa->delete();
        return redirect()->route('Tables/Index')
            ->with('success', 'Table deleted successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'number' => 'required|integer',
            'status' => 'required',
            'capacity' => 'required|integer',
        ]);
        $mesa = Mesas::findOrFail($id);
        $mesa->update($request->all());
        return redirect()->route('Tables/Index')
            ->with('success', 'Table updated successfully.');
    }
}
