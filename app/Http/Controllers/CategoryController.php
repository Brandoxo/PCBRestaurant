<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::paginate(10);
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
        Category::create($validated);
        return redirect()->route('categories.index');
    }

    public function edit(Category $category) {
        return Inertia::render('Categories/Edit', [
            'category' => $category
        ]);
    }

    public function update(Request $request, Category $category) {
        $validated = $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id,
        ]);
        $category->update($validated);
        return redirect()->route('categories.index');
    }

    public function destroy(Category $category) {
        $category->delete();
        return redirect()->route('categories.index');
    }
}
