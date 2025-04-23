<?php
/** @var \Illuminate\Database\Eloquent\Collection $orders */
?>

<x-app-layout>
    <div class="container mx-auto lg:w-2/3 p-5">
        <h1 class="text-3xl font-bold mb-2">My Orders</h1>
        <div class="bg-white rounded-lg p-3 overflow-x-auto">
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
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2 px-2">
                                <a href="{{ route('order.view', $order) }}"
                                    class="text-purple-600 hover:text-purple-500 font-medium">
                                    #{{ $order->id }}
                                </a>
                            </td>
                            <td class="py-2 px-2 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $order->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $order->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="py-2 px-2">
                                <div class="flex items-center">
                                    @if ($order->isPaid())
                                        <small class="text-white py-1 px-2 rounded bg-emerald-500">
                                            {{ $order->status }}
                                        </small>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-5 w-5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <small class="text-white py-1 px-2 rounded bg-gray-400">
                                            {{ $order->status }}
                                        </small>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    @if ($order->isPaid())
                                        Completed on {{ $order->updated_at->format('d M Y') }}
                                    @else
                                        Awaiting payment
                                    @endif
                                </div>
                            </td>
                            <td class="py-2 px-2 font-medium">${{ $order->total_price }}</td>
                            <td class="py-2 px-2 whitespace-nowrap">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                    {{ $order->items->count() }} item(s)
                                </span>
                            </td>
                            <td class="py-2 px-2">
                                <div class="flex gap-2">
                                    <!-- View Order Button -->
                                    <a href="{{ route('order.view', $order) }}" class="flex items-center justify-center p-2 text-sm font-medium text-gray-700 bg-white rounded-md hover:bg-gray-50 border border-gray-200" title="View Order Details">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    
                                    <!-- Print Order Button -->
                                    <button onclick="window.open('{{ route('order.view', $order) }}?print=1', '_blank')" class="flex items-center justify-center p-2 text-sm font-medium text-gray-700 bg-white rounded-md hover:bg-gray-50 border border-gray-200" title="Print Order">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </button>
                                    
                                    <!-- Buy Again Button -->
                                    <form action="{{ route('order.buyAgain', $order) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="flex items-center justify-center p-2 text-sm font-medium text-gray-700 bg-white rounded-md hover:bg-gray-50 border border-gray-200" title="Buy Again">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        </button>
                                    </form>
                                    
                                    @if (!$order->isPaid())
                                        <!-- Pay Button -->
                                        <form action="{{ route('cart.checkout-order', $order) }}" method="POST" class="inline">
                                            @csrf
                                            <button class="flex items-center justify-center p-2 text-sm font-medium text-white bg-amber-500 rounded-md hover:bg-amber-600" title="Pay Now">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-xl font-medium">No orders found</p>
                                    <p class="mt-1">You haven't placed any orders yet.</p>
                                    <a href="{{ route('home') }}" class="mt-4 px-4 py-2 bg-amber-500 text-white rounded-md hover:bg-amber-600">
                                        Shop Now
                                    </a>
                                </div>
                            </td>
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
