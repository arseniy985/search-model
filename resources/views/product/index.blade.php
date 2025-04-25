<?php
/** @var \Illuminate\Database\Eloquent\Collection $products */
?>

<x-app-layout>
    <div class="px-4 pt-6">
        <x-search-form :categories="$categories" />
    </div>

    @if(request()->has('query') || request()->has('category_id') || request()->has('min_price') || request()->has('max_price'))
        <div class="px-4 mb-4">
            <h2 class="text-xl font-semibold">
                @if(request('query'))
                    Search results for: "{{ request('query') }}"
                @else
                    Filter results
                @endif
                
                @if(request('category_id') && $categories->find(request('category_id')))
                    in {{ $categories->find(request('category_id'))->name }}
                @endif
                
                @if(request('min_price') || request('max_price'))
                    @if(request('min_price') && request('max_price'))
                        Price range: ${{ request('min_price') }} - ${{ request('max_price') }}
                    @elseif(request('min_price'))
                        Price from: ${{ request('min_price') }}
                    @elseif(request('max_price'))
                        Price up to: ${{ request('max_price') }}
                    @endif
                @endif
            </h2>
            <a href="{{ route('home') }}" class="text-amber-500 hover:underline">Clear filters</a>
        </div>
    @endif

    <?php if ($products->count() === 0): ?>
    <div class="text-center text-gray-600 py-16 text-xl">
        No products found
    </div>
    <?php else: ?>
    <div class="grid gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 p-5">
        @foreach($products as $product)
            <!-- Product Item -->
            <div x-data="productItem({{ json_encode([
                'id' => $product->id,
                'slug' => $product->slug,
                'image' => $product->image,
                'title' => $product->title,
                'price' => $product->price,
                'addToCartUrl' => route('cart.add', $product),
            ]) }})"
                class="border border-1 border-gray-200 rounded-md hover:border-amber-600 transition-colors bg-white">
                <a href="{{ route('product.view', $product->slug) }}" class="aspect-w-1 aspect-h-1 block overflow-hidden">
                    <img src="{{ $product->image ? Vite::asset('public/storage/' . $product->image) : asset('images/placeholder.png') }}"
                        alt="{{ $product->title }}"
                        class="w-full h-96 object-cover rounded-lg hover:scale-105 hover:rotate-1 transition-transform" />
                </a>

                <div class="p-4">
                    <h3 class="text-lg">
                        <a href="{{ route('product.view', $product->slug) }}">
                            {{ $product->title }}
                        </a>
                    </h3>
                    <h5 class="font-bold">${{ $product->price }}</h5>
                    @if($product->category)
                        <div class="mt-1">
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">
                                {{ $product->category->name }}
                            </span>
                        </div>
                    @endif
                </div>

                @auth
                    <div class="flex justify-between py-3 px-4">
                        <button class="btn-primary" @click="addToCart()">
                            Add to Cart
                        </button>
                    </div>
                @else
                    <div class="flex justify-between py-3 px-4">
                        <a class="btn-primary" href="/login">
                            Sign in to order the product
                        </a>
                    </div>
                @endauth
            </div>
            <!--/ Product Item -->
        @endforeach
    </div>
    {{ $products->links() }}
    <?php endif; ?>

    {{-- Скрипт поиска перемещен в отдельный компонент и подключается через app.js --}}
</x-app-layout>
