<?php

namespace App\Http\Controllers\Api;

use App\Customer;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CustomerIndexRequest;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customer.
     *
     * @SWG\Get(
     *  path="/customers",
     *  tags={"Customer"},
     *  @SWG\Parameter(ref="#/parameters/page_in_query"),
     *  @SWG\Parameter(ref="#/parameters/limit_in_query"),
     *  @SWG\Parameter(
     *      ref="$/parameters/sort_by_in_query",
     *      enum={"id","first_name","last_name","email","phone","created_at"},
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
     * @return \Illuminate\Http\Response
     */
    public function index(CustomerIndexRequest $request)
    {
        $customers = Customer::with(['shippingAddress'])
            ->search($request->input('search'))
            ->orderBy($request->input('sort_by', 'created_at'), $request->input('order', 'desc'))
            ->paginate($request->input('limit', 15));

        return CustomerResource::collection($customers);
    }

    /**
     * Store a newly created customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified customer.
     *
     * @SWG\Get(
     *  path="/customers/{customerId}",
     *  tags={"Customer"},
     *  @SWG\Parameter(
     *      ref="$/parameters/param_in_path_required",
     *      name="customerId",
     *      description="Customer ID",
     *  ),
     *  @SWG\Response(response=200, description="Success response"),
     * )
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return new CustomerResource($customer->load(['shippingAddress']));
    }

    /**
     * Update the specified customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified customer from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
