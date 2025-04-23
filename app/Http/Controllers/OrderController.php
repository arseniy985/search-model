<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CartItem;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $orders = Order::query()
            ->where('created_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('order.index', compact('orders'));
    }

    public function view(Order $order, Request $request)
    {
        $user = \request()->user();
        if ($order->created_by !== $user->id) {
            return response("You don't have permission to view this order", 403);
        }

        // Если запрос с параметром print, возвращаем версию для печати
        if ($request->has('print')) {
            return view('order.print', compact('order'));
        }

        return view('order.view', compact('order'));
    }

    /**
     * Добавить все товары из заказа в корзину (Buy Again)
     */
    public function buyAgain(Order $order, Request $request)
    {
        $user = $request->user();
        
        // Проверка, принадлежит ли заказ пользователю
        if ($order->created_by !== $user->id) {
            return redirect()->route('order.index')->with('error', 'You don\'t have permission to perform this action');
        }
        
        // Получаем все товары из заказа
        $orderItems = OrderItem::where('order_id', $order->id)->get();
        
        foreach ($orderItems as $item) {
            // Проверяем, есть ли уже товар в корзине
            $cartItem = CartItem::where([
                'user_id' => $user->id,
                'product_id' => $item->product_id
            ])->first();
            
            if ($cartItem) {
                // Если товар уже в корзине, увеличиваем количество
                $cartItem->quantity += $item->quantity;
                $cartItem->update();
            } else {
                // Иначе добавляем новый товар в корзину
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity
                ]);
            }
        }
        
        return redirect()->route('cart.index')->with('success', 'All items from the order have been added to your cart');
    }
}
