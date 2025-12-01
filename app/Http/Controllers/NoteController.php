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
}
