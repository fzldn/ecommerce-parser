<?php

namespace App\Http\Controllers\Api;

use App\Brand;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    /**
     * Display a listing of the brand.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'page' => ['filled', 'integer', 'min:1'],
            'limit' => ['filled', 'integer', 'min:0'],
            'sort_by' => ['filled', Rule::in([
                'id',
                'name',
                'created_at'
            ])],
            'order' => ['filled', Rule::in(['asc', 'desc'])],
            'search' => ['string']
        ]);

        $search = $request->input('search');
        $brands = Brand::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })
            ->orderBy($request->input('sort_by', 'created_at'), $request->input('order', 'desc'))
            ->paginate($request->input('limit', 15));

        return BrandResource::collection($brands);
    }

    /**
     * Store a newly created brand in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified brand.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        return new BrandResource($brand);
    }

    /**
     * Update the specified brand in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        //
    }

    /**
     * Remove the specified brand from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        //
    }
}
