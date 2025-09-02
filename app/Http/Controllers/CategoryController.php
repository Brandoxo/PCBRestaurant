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
        return Inertia::render('Categories/Create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|unique:categories',
        ]);
        Categories::create($validated);
        return redirect()->route('categories.index');
    }

    public function edit(Categories $category) {
        return Inertia::render('Categories/Edit', [
            'category' => $category
        ]);
    }

    public function update(Request $request, Categories $category) {
        $validated = $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id,
        ]);
        $category->update($validated);
        return redirect()->route('categories.index');
    }

    public function destroy(Categories $category) {
        $category->delete();
        return redirect()->route('categories.index');
    }
}
