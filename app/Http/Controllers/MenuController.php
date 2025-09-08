<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Products;
use App\Models\Categories; 
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $products = Products::all();
        $categories = Categories::all();
        return Inertia::render('Menu/Index', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    public function create()
    {   
        $products = Products::all();
        $categories = Categories::all();
        return Inertia::render('Menu/Create', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'name' => 'required',
            'sku' => 'required',
            'description' => 'required',
            'price' => 'required',
            'cost' => 'required',
            'tax_rate' => 'nullable',
            'is_active' => 'required|boolean',
            'created_at' => 'nullable',
            'updated_at' => 'nullable',
        ]);

        $data = $request->all();

        if($request->hasFile('image')){
            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $destinationPath = 'assets/uploads/';
            $file->move($destinationPath, $filename);
            $data['image'] = $destinationPath . $filename;
        }

        Products::create($data);

        return redirect()->route('Menu/Index')->with('success', 'Producto creado exitosamente.');
    }

    public function update(Request $request, $id) {

        $request->validate([
            'category_id' => 'required',
            'name' => 'required',
            'sku' => 'required',
            'description' => 'required',
            'price' => 'required',
            'cost' => 'required',
            'tax_rate' => 'nullable',
            'is_active' => 'required|boolean',
            'created_at' => 'nullable',
            'updated_at' => 'nullable',
        ]);

        $data = $request->all();

        if($request->hasFile('image')){
            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $destinationPath = 'assets/uploads/';
            $file->move($destinationPath, $filename);
            $data['image'] = $destinationPath . $filename;
        }

        $products = Products::findOrFail($id);
        $products->update($data);
        return redirect()->route('Menu/Index')->with('success', 'Producto actualizado exitosamente.');

    }


    public function destroy($id) {

        $products = Products::findOrFail($id);
        $products->delete($id);
        return redirect()->route('Menu/Index')->with('succes', 'Producto Eliminado exitosamente.');

    }

}
