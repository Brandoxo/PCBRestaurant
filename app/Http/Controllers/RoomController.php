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
}
