<x-app-layout>
    <div class="container mx-auto lg:w-2/3 p-5">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold">Order #{{ $order->id }} (Admin View)</h1>
            <a href="{{ route('admin.orders.index') }}" class="btn-primary">Back to Orders</a>
        </div>
        
        <div class="bg-white rounded-lg p-3">
            <h3 class="text-xl font-semibold mb-2">Order Details</h3>
            <table class="w-full mb-4">
                <tbody>
                    <tr>
                        <td class="font-bold py-1 px-2">Order #</td>
                        <td>{{ $order->id }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold py-1 px-2">Customer</td>
                        <td>
                            <a href="{{ route('admin.customers.orders', $order->created_by) }}" class="text-purple-600 hover:text-purple-500">
                                {{ $order->user->name }} ({{ $order->user->email }})
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="font-bold py-1 px-2">Order Date</td>
                        <td>{{ $order->created_at }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold py-1 px-2">Order Status</td>
                        <td>
                            <span class="text-white py-1 px-2 rounded {{ $order->isPaid() ? 'bg-emerald-500' : 'bg-gray-400' }}">
                                {{ $order->status }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="font-bold py-1 px-2">Payment ID</td>
                        <td>{{ $order->payment ? $order->payment->id : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold py-1 px-2">Payment Status</td>
                        <td>{{ $order->payment ? $order->payment->status : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold py-1 px-2">Total</td>
                        <td>${{ $order->total_price }}</td>
                    </tr>
                </tbody>
            </table>

            <h3 class="text-xl font-semibold mb-2">Ordered Items</h3>
            <table class="w-full">
                <thead>
                    <tr class="border-b-2">
                        <th class="text-left p-2">Product</th>
                        <th class="text-center p-2">Price</th>
                        <th class="text-center p-2">Quantity</th>
                        <th class="text-right p-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr class="border-b">
                            <td class="py-2 px-2">
                                <div class="flex items-center">
                                    <img src="{{ Vite::asset('public/storage/' . $item->product->image) }}" 
                                        alt="{{ $item->product->title }}" 
                                        class="w-16 h-16 object-cover mr-3">
                                    <div>
                                        <a href="{{ route('product.view', $item->product) }}" class="text-purple-600 hover:text-purple-500">
                                            {{ $item->product->title }}
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2 px-2 text-center">${{ $item->unit_price }}</td>
                            <td class="py-2 px-2 text-center">{{ $item->quantity }}</td>
                            <td class="py-2 px-2 text-right">${{ $item->unit_price * $item->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right font-bold py-2 px-2">Total:</td>
                        <td class="text-right py-2 px-2">${{ $order->total_price }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</x-app-layout> 