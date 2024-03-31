<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\ProductStoreRequest;
use App\Models\Product;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|Application|Factory
    {
        $product = Product::with('categories')->get();

        return view('index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|Application|Factory
    {
        $categories = Product::all();

        return view('products.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request): Product
    {
        $data = $request->validated();

        $image = $data ['image'];
        $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();
        $image->move(
            storage_path() . '/app/public/products/images',
            $imageName
        );

        $product = new Product();

        $product->name = $data['name'];
        $product->description = $data['description'] ?? null;
        $product->content = $data['content'];
        $product->image = $imageName;
        $product->article = $data['article'];

        $product->save();

        if (array_key_exists('category_ids', $data)) {
            $product->categories()->attach($data['category_ids']);
        }

        $product->load('categories');

        return $product;
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View|Application|Factory
    {
        return view('products.show', compact('product'));
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
    public function destroy(Product $product): ?bool
    {
        return $product->delete();
    }
}
