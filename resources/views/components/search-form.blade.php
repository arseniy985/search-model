<form action="{{ route('products.search') }}" method="GET" class="mb-8">
    <div class="flex flex-col space-y-4 md:flex-row md:space-y-0 md:space-x-4">
        <div class="w-full md:w-1/3">
            <label for="query" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    id="query" 
                    name="query" 
                    placeholder="Search products..." 
                    class="w-full pl-10 px-4 py-2 border rounded focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                    value="{{ request('query') }}"
                    autocomplete="off"
                >
                <div id="searchSuggestions" class="absolute z-10 w-full bg-white shadow-lg rounded-b border border-gray-200 mt-1 overflow-hidden max-h-80 overflow-y-auto hidden"></div>
            </div>
        </div>

        <div class="w-full md:w-1/4">
            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <select name="category_id" id="category_id" class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="w-full md:w-1/6">
            <label for="min_price" class="block text-sm font-medium text-gray-700 mb-1">Min Price</label>
            <input 
                type="number" 
                id="min_price" 
                name="min_price"
                placeholder="$" 
                class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                value="{{ request('min_price') }}"
                min="0"
                step="0.01"
            >
        </div>

        <div class="w-full md:w-1/6">
            <label for="max_price" class="block text-sm font-medium text-gray-700 mb-1">Max Price</label>
            <input 
                type="number" 
                id="max_price" 
                name="max_price" 
                placeholder="$" 
                class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                value="{{ request('max_price') }}"
                min="0"
                step="0.01"
            >
        </div>

        <div class="w-full md:w-1/6 flex items-end">
            <button type="submit" class="w-full px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded transition duration-200">
                Search
            </button>
        </div>
    </div>
</form> 