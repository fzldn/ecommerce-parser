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
     * @SWG\Get(
     *  path="/orders",
     *  tags={"Order"},
     *  @SWG\Parameter(ref="#/parameters/page_in_query"),
     *  @SWG\Parameter(ref="#/parameters/limit_in_query"),
     *  @SWG\Parameter(
     *      ref="$/parameters/sort_by_in_query",
     *      enum={"id","shipping_price","created_at"},
     *      default="created_at"
     *  ),
     *  @SWG\Parameter(ref="#/parameters/order_in_query"),
     *  @SWG\Response(response=200, description="Success response"),
     * )
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
     * @SWG\Get(
     *  path="/orders/{orderId}",
     *  tags={"Order"},
     *  @SWG\Parameter(
     *      ref="$/parameters/param_in_path_required",
     *      name="orderId",
     *      description="Order ID",
     *  ),
     *  @SWG\Response(response=200, description="Success response"),
     * )
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
