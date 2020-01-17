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
     * @SWG\Get(
     *  path="/brands",
     *  tags={"Brand"},
     *  @SWG\Parameter(ref="#/parameters/page_in_query"),
     *  @SWG\Parameter(ref="#/parameters/limit_in_query"),
     *  @SWG\Parameter(
     *      ref="$/parameters/sort_by_in_query",
     *      enum={"id","name","created_at"},
     *      default="created_at"
     *  ),
     *  @SWG\Parameter(ref="#/parameters/order_in_query"),
     *  @SWG\Parameter(
     *      name="search",
     *      in="query",
     *      description="Search",
     *      type="string"
     *  ),
     *  @SWG\Response(response=200, description="Success response"),
     * )
     *
     * @param  \Illuminate\Http\Request  $request
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
     * @SWG\Get(
     *  path="/brands/{brandId}",
     *  tags={"Brand"},
     *  @SWG\Parameter(
     *      ref="$/parameters/param_in_path_required",
     *      name="brandId",
     *      description="Brand ID",
     *  ),
     *  @SWG\Response(response=200, description="Success response"),
     * )
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
