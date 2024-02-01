<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $categories = Category::with('products')->paginate(10);

        return response()
            ->view('dashboard.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return response()
            ->view('dashboard.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        request()->validate([
            'name' => 'required|unique:categories,name',
        ]);

        Category::create(request()->only(['name']));

        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', 'Category successfully created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): Response
    {
        return response()
            ->view('dashboard.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Category $category)
    {
        request()->validate([
            'name' => "required|unique:categories,name,$category->id",
        ]);

        $category->name = request()->input('name');
        $category->save();

        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', 'Category successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
