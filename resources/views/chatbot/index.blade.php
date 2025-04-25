<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-semibold mb-4">AI Shopping Assistant</h1>
                    <p class="mb-4 text-gray-600">Ask me about products and I'll help you find what you're looking for!</p>
                    
                    <div id="chat-container" class="border rounded-lg p-4 h-96 overflow-y-auto mb-4 bg-gray-50">
                        <div class="chat-message assistant">
                            <div class="flex items-start">
                                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3 bg-blue-100 rounded-lg py-2 px-3 max-w-3xl">
                                    <p>Hi! I'm your HyperX Store assistant. How can I help you find products today?</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex">
                        <input 
                            type="text" 
                            id="chat-input" 
                            class="flex-1 rounded-l-lg border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="Ask about products (e.g., 'I need a gaming keyboard')"
                        >
                        <button 
                            id="send-button"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-r-lg"
                        >
                            Send
                        </button>
                    </div>
                    
                    <div id="suggested-products" class="mt-6 hidden">
                        <h3 class="text-lg font-medium mb-3">Suggested Products</h3>
                        <div id="products-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- Products will be dynamically added here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatContainer = document.getElementById('chat-container');
            const chatInput = document.getElementById('chat-input');
            const sendButton = document.getElementById('send-button');
            const suggestedProducts = document.getElementById('suggested-products');
            const productsContainer = document.getElementById('products-container');
            
            let isWaitingForResponse = false;
            
            // Function to add a message to the chat
            function addMessage(message, isUser = false) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `chat-message ${isUser ? 'user' : 'assistant'} mt-4`;
                
                let iconHtml = '';
                if (isUser) {
                    iconHtml = `
                        <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    `;
                } else {
                    iconHtml = `
                        <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    `;
                }
                
                messageDiv.innerHTML = `
                    <div class="flex items-start">
                        ${iconHtml}
                        <div class="ml-3 ${isUser ? 'bg-indigo-100' : 'bg-blue-100'} rounded-lg py-2 px-3 max-w-3xl">
                            <p>${message}</p>
                        </div>
                    </div>
                `;
                
                chatContainer.appendChild(messageDiv);
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
            
            // Function to display products
            function displayProducts(products) {
                if (products.length === 0) {
                    suggestedProducts.classList.add('hidden');
                    return;
                }
                
                suggestedProducts.classList.remove('hidden');
                productsContainer.innerHTML = '';
                
                products.forEach(product => {
                    const productCard = document.createElement('div');
                    productCard.className = 'border rounded-lg overflow-hidden shadow-sm';
                    
                    const imageUrl = product.image || '{{ asset("images/placeholder.jpg") }}';
                    
                    productCard.innerHTML = `
                        <div class="h-40 bg-gray-200 overflow-hidden">
                            <img src="${imageUrl}" alt="${product.title}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4">
                            <h4 class="font-medium text-gray-900 truncate">${product.title}</h4>
                            <p class="text-gray-600 mt-1">$${product.price.toFixed(2)}</p>
                            <a href="${product.url}" class="mt-2 inline-block bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-1 rounded">
                                View Product
                            </a>
                        </div>
                    `;
                    
                    productsContainer.appendChild(productCard);
                });
            }
            
            // Function to handle sending a message
            function sendMessage() {
                if (isWaitingForResponse || !chatInput.value.trim()) return;
                
                const message = chatInput.value.trim();
                addMessage(message, true);
                
                chatInput.value = '';
                isWaitingForResponse = true;
                
                // Add loading indicator
                const loadingDiv = document.createElement('div');
                loadingDiv.className = 'chat-message assistant mt-4';
                loadingDiv.innerHTML = `
                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 bg-blue-100 rounded-lg py-2 px-3">
                            <p>Thinking...</p>
                        </div>
                    </div>
                `;
                chatContainer.appendChild(loadingDiv);
                chatContainer.scrollTop = chatContainer.scrollHeight;
                
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
                    // Remove loading indicator
                    chatContainer.removeChild(loadingDiv);
                    
                    // Add the assistant's response
                    addMessage(data.message);
                    
                    // Display suggested products
                    displayProducts(data.products);
                    
                    isWaitingForResponse = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    chatContainer.removeChild(loadingDiv);
                    addMessage('Sorry, there was an error processing your request. Please try again later.');
                    isWaitingForResponse = false;
                });
            }
            
            // Event listeners
            sendButton.addEventListener('click', sendMessage);
            
            chatInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
        });
    </script>
    @endpush
</x-app-layout> 