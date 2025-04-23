<x-app-layout>
    <div class="container mx-auto lg:w-2/3 p-5">
        <h1 class="text-3xl font-bold mb-2">All Orders (Admin)</h1>
        <div class="bg-white rounded-lg p-3 overflow-x-auto">
            <table class="table-auto w-full">
                <thead>
                    <tr class="border-b-2">
                        <th class="text-left p-2">Order #</th>
                        <th class="text-left p-2">Customer</th>
                        <th class="text-left p-2">Date</th>
                        <th class="text-left p-2">Status</th>
                        <th class="text-left p-2">SubTotal</th>
                        <th class="text-left p-2">Items</th>
                        <th class="text-left p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="border-b">
                            <td class="py-1 px-2">
                                <a href="{{ route('admin.orders.view', $order) }}"
                                    class="text-purple-600 hover:text-purple-500">
                                    #{{ $order->id }}
                                </a>
                            </td>
                            <td class="py-1 px-2">
                                <a href="{{ route('admin.customers.orders', $order->created_by) }}"
                                    class="text-purple-600 hover:text-purple-500">
                                    {{ $order->user->name }}
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
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout> 