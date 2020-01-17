<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @SWG\Swagger(
     *  produces={"application/json"},
     *  @SWG\Info(title="e-Commerce Parser API Documentation", version="1.0.0"),
     * )
     *
     * @SWG\Tag(name="Brand", description="Product Brands")
     * @SWG\Tag(name="Category", description="Product Categories")
     * @SWG\Tag(name="Product", description="Products")
     * @SWG\Tag(name="Customer", description="Customers")
     * @SWG\Tag(name="Order", description="Orders")
     *
     * @SWG\Parameter(
     *  parameter="param_in_path_required",
     *  name="param",
     *  in="path",
     *  type="string",
     *  required=true,
     *  description="Param",
     * )
     *
     * @SWG\Parameter(
     *  parameter="page_in_query",
     *  name="page",
     *  in="query",
     *  description="Current page",
     *  type="integer",
     *  default=1,
     * )
     *
     * @SWG\Parameter(
     *  parameter="limit_in_query",
     *  name="limit",
     *  in="query",
     *  description="Items per page",
     *  type="integer",
     *  default=15,
     * )
     *
     * @SWG\Parameter(
     *  parameter="sort_by_in_query",
     *  name="sort_by",
     *  in="query",
     *  description="Sort by",
     *  type="string",
     * )
     *
     * @SWG\Parameter(
     *  parameter="order_in_query",
     *  name="order",
     *  in="query",
     *  description="Sort ordering",
     *  type="string",
     *  enum={"asc","desc"},
     *  default="desc",
     * )
     */
}
