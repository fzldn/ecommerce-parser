<?php

namespace App\Http\Controllers\Api;

use App\Brand;
use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductIndexRequest;
use App\Http\Resources\ProductResource;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the product.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductIndexRequest $request)
    {
        $products = Product::with(['brand', 'categories'])
            ->search($request->input('search'))
            ->orderBy($request->input('sort_by', 'created_at'), $request->input('order', 'desc'))
            ->paginate($request->input('limit', 15));

        return ProductResource::collection($products);
    }

    /**
     * Display a listing of the product by brand.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexByBrand(ProductIndexRequest $request, Brand $brand)
    {
        $search = $request->input('search');
        $products = Product::with(['brand', 'categories'])
            ->whereHas('brand', function ($query) use ($brand) {
                $query->where('id', $brand->id);
            })
            ->search($request->input('search'))
            ->orderBy($request->input('sort_by', 'created_at'), $request->input('order', 'desc'))
            ->paginate($request->input('limit', 15));

        return ProductResource::collection($products);
    }

    /**
     * Display a listing of the product by category.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexByCategory(ProductIndexRequest $request, Category $category)
    {
        $search = $request->input('search');
        $products = Product::with(['brand', 'categories'])
            ->whereHas('categories', function ($query) use ($category) {
                $query->where('id', $category->id);
            })
            ->search($request->input('search'))
            ->orderBy($request->input('sort_by', 'created_at'), $request->input('order', 'desc'))
            ->paginate($request->input('limit', 15));

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified product.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResource($product->load(['brand', 'categories']));
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
