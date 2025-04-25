<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>{{ config('app.name', 'Laravel E-commerce Website') }}</title> --}}
    <title>HyperX Store</title>
    <link rel="icon" type="image/x-icon"
        href="data:image/png;base64,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" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
        
        <!-- Toast -->
        <div x-data="toast" x-show="visible" x-transition x-cloak @notify.window="show($event.detail.message)"
            class="fixed w-[400px] left-1/2 -ml-[200px] top-16 py-2 px-4 pb-4 bg-emerald-500 text-white">
            <div class="font-semibold" x-text="message"></div>
            <button @click="close"
                class="absolute flex items-center justify-center right-2 top-2 w-[30px] h-[30px] rounded-full hover:bg-black/10 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <!-- Progress -->
            <div>
                <div class="absolute left-0 bottom-0 right-0 h-[6px] bg-black/10" :style="{ 'width': `${percent}%` }"></div>
            </div>
        </div>
        <!--/ Toast -->
        
        <!-- Floating Chatbot -->
        <div id="floating-chatbot" class="fixed bottom-4 right-4 z-50">
            <div x-data="{ 
                open: false,
                messages: [],
                products: [],
                isLoading: false,
                chatInput: '',
                
                init() {
                    this.messages.push({
                        isUser: false,
                        content: `
# 👋 Здравствуйте!

Я AI-ассистент HyperX Store, готов помочь вам найти идеальные товары в нашем каталоге.

**Что я могу для вас сделать:**
- Помочь найти товары по описанию
- Рекомендовать популярные товары
- Ответить на вопросы о нашем ассортименте

Чем я могу помочь вам сегодня?`
                    });
                },
                
                toggleChat() {
                    this.open = !this.open;
                    if (this.open) {
                        setTimeout(() => {
                            this.$refs.chatContainer.scrollTop = this.$refs.chatContainer.scrollHeight;
                            this.$refs.chatInput.focus();
                        }, 100);
                    }
                },
                
                sendMessage() {
                    if (this.isLoading || !this.chatInput.trim()) return;
                    
                    const message = this.chatInput.trim();
                    this.messages.push({
                        isUser: true,
                        content: message
                    });
                    
                    this.chatInput = '';
                    this.isLoading = true;
                    
                    // Scroll to bottom
                    setTimeout(() => {
                        this.$refs.chatContainer.scrollTop = this.$refs.chatContainer.scrollHeight;
                    }, 100);
                    
                    // Send API request
                    fetch('{{ route("chatbot.query") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ message })
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.isLoading = false;
                        
                        // Add the assistant's response
                        this.messages.push({
                            isUser: false,
                            content: data.message
                        });
                        
                        // Update products
                        this.products = data.products;
                        
                        // Scroll to bottom
                        setTimeout(() => {
                            this.$refs.chatContainer.scrollTop = this.$refs.chatContainer.scrollHeight;
                        }, 100);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.isLoading = false;
                        
                        this.messages.push({
                            isUser: false,
                            content: 'Sorry, there was an error processing your request. Please try again later.'
                        });
                        
                        // Scroll to bottom
                        setTimeout(() => {
                            this.$refs.chatContainer.scrollTop = this.$refs.chatContainer.scrollHeight;
                        }, 100);
                    });
                }
            }"
            @keydown.escape="open = false">
                <!-- Chat Button -->
                <button 
                    @click="toggleChat" 
                    class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg transition duration-200 flex items-center justify-center"
                    :class="{ 'rotate-45 transform': open }"
                >
                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
                <!-- Chat Window -->
                <div 
                    x-show="open" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-90"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-90"
                    class="absolute bottom-16 right-0 bg-white rounded-lg shadow-xl overflow-hidden flex flex-col"
                    style="height: 600px; width: 500px;"
                >
                    <!-- Chat Header -->
                    <div class="bg-blue-600 p-4 text-white">
                        <h3 class="text-xl font-semibold">AI Shopping Assistant</h3>
                        <p class="text-sm opacity-90">Ask me about products in our catalog!</p>
                    </div>
                    
                    <!-- Chat Messages -->
                    <div x-ref="chatContainer" class="flex-1 overflow-y-auto p-4 bg-gray-50 prose prose-sm max-w-none">
                        <template x-for="(message, index) in messages" :key="index">
                            <div :class="`chat-message ${message.isUser ? 'user' : 'assistant'} mt-3`">
                                <div class="flex items-start">
                                    <div :class="`w-10 h-10 rounded-full flex items-center justify-center text-white ${message.isUser ? 'bg-gray-400' : 'bg-blue-600'}`">
                                        <template x-if="message.isUser">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                        <template x-if="!message.isUser">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                    </div>
                                    <div :class="`ml-3 py-3 px-4 rounded-lg max-w-[90%] ${message.isUser ? 'bg-indigo-100' : 'bg-white border border-blue-100 shadow-sm'}`" x-html="message.content">
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <!-- Loading Indicator -->
                        <div x-show="isLoading" class="chat-message assistant mt-3">
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3 bg-white border border-blue-100 py-3 px-4 rounded-lg shadow-sm">
                                    <div class="flex space-x-2">
                                        <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce"></div>
                                        <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce delay-100"></div>
                                        <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce delay-200"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Products (if available) -->
                    <div x-show="products.length > 0" class="max-h-48 overflow-y-auto border-t border-gray-200 bg-white p-3">
                        <p class="text-sm text-gray-600 mb-2 font-semibold">Рекомендуемые товары:</p>
                        <div class="grid grid-cols-2 gap-3">
                            <template x-for="product in products" :key="product.id">
                                <a :href="product.url" class="block border rounded overflow-hidden hover:shadow-md transition group">
                                    <div class="h-20 bg-gray-100 flex items-center justify-center overflow-hidden">
                                        <img :src="product.image || '{{ asset('images/placeholder.jpg') }}'" :alt="product.title" class="w-full h-full object-cover group-hover:scale-105 transition">
                                    </div>
                                    <div class="p-2">
                                        <p class="text-sm font-medium text-gray-900 truncate group-hover:text-blue-600" x-text="product.title"></p>
                                        <div class="flex justify-between items-center mt-1">
                                            <p class="text-sm font-bold text-blue-600" x-text="'$' + product.price.toFixed(2)"></p>
                                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Подробнее</span>
                                        </div>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </div>
                    
                    <!-- Chat Input -->
                    <div class="border-t border-gray-200 p-3 bg-white">
                        <div class="flex items-center">
                            <input 
                                x-ref="chatInput"
                                x-model="chatInput"
                                @keydown.enter="sendMessage"
                                type="text" 
                                placeholder="Спросите о товарах..." 
                                class="flex-1 border border-gray-300 rounded-l-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                            <button 
                                @click="sendMessage" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-r-lg transition flex items-center"
                            >
                                <span class="mr-1 hidden md:inline">Отправить</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <div class="mt-2 text-xs text-gray-500 text-center">
                            Например: "Что у вас есть для геймеров?" или "Ищу подарок для друга"
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @stack('scripts')
</body>

</html>
