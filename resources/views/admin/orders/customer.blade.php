<x-app-layout>
    <div class="container mx-auto lg:w-2/3 p-5">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold">Orders for {{ $user->name }}</h1>
            <a href="{{ route('admin.orders.index') }}" class="btn-primary">Back to All Orders</a>
        </div>
        
        <div class="mb-4 bg-white rounded-lg p-3">
            <h3 class="text-xl font-semibold mb-2">Customer Information</h3>
            <table class="w-full">
                <tbody>
                    <tr>
                        <td class="font-bold py-1 px-2">Name</td>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold py-1 px-2">Email</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold py-1 px-2">Registered</td>
                        <td>{{ $user->created_at }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold py-1 px-2">Total Orders</td>
                        <td>{{ $orders->total() }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="bg-white rounded-lg p-3 overflow-x-auto">
            <h3 class="text-xl font-semibold mb-2">Order History</h3>
            <table class="table-auto w-full">
                <thead>
                    <tr class="border-b-2">
                        <th class="text-left p-2">Order #</th>
                        <th class="text-left p-2">Date</th>
                        <th class="text-left p-2">Status</th>
                        <th class="text-left p-2">SubTotal</th>
                        <th class="text-left p-2">Items</th>
                        <th class="text-left p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr class="border-b">
                            <td class="py-1 px-2">
                                <a href="{{ route('admin.orders.view', $order) }}"
                                    class="text-purple-600 hover:text-purple-500">
                                    #{{ $order->id }}
                                </a>
                            </td>
                            <td class="py-1 px-2 whitespace-nowrap">{{ $order->created_at }}</td>
                            <td class="py-1 px-2">
                                <small
                                    class="text-white py-1 px-2 rounded
                                {{ $order->isPaid() ? 'bg-emerald-500' : 'bg-gray-400' }}">
                                    {{ $order->status }}
                                </small>
                            </td>
                            <td class="py-1 px-2">${{ $order->total_price }}</td>
                            <td class="py-1 px-2 whitespace-nowrap">{{ $order->items->count() }} item(s)</td>
                            <td class="py-1 px-2 flex gap-2 w-[100px]">
                                <a href="{{ route('admin.orders.view', $order) }}"
                                    class="flex items-center py-1 px-3 btn-primary whitespace-nowrap">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-gray-500">No orders found for this customer</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout> 