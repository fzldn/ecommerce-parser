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
