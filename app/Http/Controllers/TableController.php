<?php

namespace App\Http\Controllers;
use App\Models\Mesas;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $mesas = Mesas::paginate(10); // O Mesas::all()
        return inertia('Tables/Index', [
            'mesas' => $mesas
        ]);
    }

    public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|max:255',
      'number' => 'required|integer',
      'status' => 'required',
      'capacity' => 'required|integer',
    ]);
    Mesas::create($request->all());
    return redirect()->route('tables.index')
      ->with('success', 'Table created successfully.');
  }

    public function create()
    {
        return inertia('Tables/Create');
    }
}
