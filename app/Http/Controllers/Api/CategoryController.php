<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the category.
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
        $categories = Category::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })
            ->orderBy($request->input('sort_by', 'created_at'), $request->input('order', 'desc'))
            ->paginate($request->input('limit', 15));

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified category.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
