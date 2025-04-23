<x-app-layout>
    <div class="w-[400px] mx-auto bg-emerald-500 py-2 px-3 text-white rounded text-center">
        @if ($customer)
            {{ $customer->first_name . ' ' . $customer->last_name }}, Your order has been completed!!
        @else
            {{ $user->name }}, Your order has been completed!!
        @endif
    </div>
</x-app-layout>
