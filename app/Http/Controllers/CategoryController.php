<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index() {
        $categories = Categories::paginate(10);
        return Inertia::render('Categories/Index', [
            'categories' => $categories
        ]);
    }

    public function create() {
        $categories = Categories::all();
        return Inertia::render('Categories/Create', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|unique:categories',
            'description' => 'required'
        ]);
        Categories::create($request->all());
        return redirect()->route('Categories/Index')
        ->with('succes', 'Category created succesfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $id,
            'description' => 'required|max:255'
    ]);

    $category = Categories::findOrFail($id);
    $category->name = $request->name;
    $category->description = $request->description;
    $category->save();

    return redirect()->route('Categories/Index')
        ->with('success', 'Category updated successfully.');
}

    public function destroy($id) {
        $category = Categories::findOrFail($id);
        $category->delete();
        return redirect()->route('Categories/Index')
            ->with('success', 'Category deleted successfully.');
    }
}
