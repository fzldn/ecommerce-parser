<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /**
     * Display a listing of the order.
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
                'shipping_price',
                'created_at'
            ])],
            'order' => ['filled', Rule::in(['asc', 'desc'])]
        ]);

        $search = $request->input('search');
        $orders = Order::with(['customer.shippingAddress', 'items', 'discounts'])
            ->orderBy($request->input('sort_by', 'created_at'), $request->input('order', 'desc'))
            ->paginate($request->input('limit', 15));

        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified order.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return new OrderResource($order->load([
            'customer.shippingAddress',
            'items.product.brand',
            'items.product.categories',
            'discounts'
        ]));
    }

    /**
     * Update the specified order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified order from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
