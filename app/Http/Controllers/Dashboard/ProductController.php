<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $products = Product::with(['user', 'category', 'orderItems'])->paginate(10);

        return response()
            ->view('dashboard.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $categories = Category::all();

        return response()
            ->view('dashboard.product.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        request()->validate([
            'name' => 'required|max:100',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category' => 'required'
        ]);

        $product = new Product();
        $product->name = request()->input('name');
        $product->description = request()->input('description');
        $product->price = request()->input('price');
        $product->quantity_in_stock = request()->input('quantity');
        $product->category_id = request()->input('category');
        $product->user_id = auth()->id();
        $product->save();

        return redirect()
            ->route('dashboard.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
