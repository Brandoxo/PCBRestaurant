<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notes;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Notes::all();
        return view('Dashboard/Index', compact('notes'));
        
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $note = new Notes();
        $note->content = $request->input('content');
        $note->save();

        return redirect()->back()->with('success', 'Note added successfully!');
    }
}
