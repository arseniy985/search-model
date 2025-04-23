<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.product', 'payment'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function view(Order $order)
    {
        $order->load(['items.product', 'payment', 'user']);
        
        return view('admin.orders.view', compact('order'));
    }

    public function customerOrders(User $user)
    {
        $orders = Order::with(['items.product', 'payment'])
            ->where('created_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.orders.customer', compact('orders', 'user'));
    }
}
