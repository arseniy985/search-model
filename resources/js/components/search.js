// Компонент для поисковых подсказок
export function initSearchSuggestions() {
    const searchInput = document.getElementById('query');
    const suggestionContainer = document.getElementById('searchSuggestions');
    
    if (!searchInput || !suggestionContainer) {
        return; // Выходим, если элементы не найдены
    }
    
    let debounceTimer;
    
    searchInput.addEventListener('input', function() {
        const query = this.value;
        
        // Очищаем предыдущий таймер
        clearTimeout(debounceTimer);
        
        if (query.length < 2) {
            suggestionContainer.classList.add('hidden');
            return;
        }
        
        // Устанавливаем задержку перед запросом для дебаунсинга
        debounceTimer = setTimeout(() => {
            // Показываем индикатор загрузки
            suggestionContainer.innerHTML = '<div class="p-2 text-center text-gray-500">Searching...</div>';
            suggestionContainer.classList.remove('hidden');
            
            fetch(`/api/search-suggestions?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        suggestionContainer.innerHTML = '';
                        
                        // Группируем результаты по типу
                        const products = data.filter(item => item.type === 'product');
                        const categories = data.filter(item => item.type === 'category');
                        
                        // Добавляем категории
                        if (categories.length > 0) {
                            const categoriesHeader = document.createElement('div');
                            categoriesHeader.className = 'p-2 bg-gray-100 text-xs font-semibold text-gray-600';
                            categoriesHeader.textContent = 'CATEGORIES';
                            suggestionContainer.appendChild(categoriesHeader);
                            
                            categories.forEach(category => {
                                const item = document.createElement('div');
                                item.className = 'p-2 hover:bg-gray-100 cursor-pointer flex items-center';
                                
                                const icon = document.createElement('span');
                                icon.className = 'mr-2 text-gray-500';
                                icon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>';
                                
                                const content = document.createElement('span');
                                content.textContent = category.title;
                                
                                item.appendChild(icon);
                                item.appendChild(content);
                                
                                item.addEventListener('click', function() {
                                    window.location.href = `/?category_id=${category.id}`;
                                });
                                
                                suggestionContainer.appendChild(item);
                            });
                        }
                        
                        // Добавляем продукты
                        if (products.length > 0) {
                            const productsHeader = document.createElement('div');
                            productsHeader.className = 'p-2 bg-gray-100 text-xs font-semibold text-gray-600';
                            productsHeader.textContent = 'PRODUCTS';
                            suggestionContainer.appendChild(productsHeader);
                            
                            products.forEach(product => {
                                const item = document.createElement('div');
                                item.className = 'p-2 hover:bg-gray-100 cursor-pointer flex items-center';
                                
                                // Создаем изображение товара
                                const imageContainer = document.createElement('div');
                                imageContainer.className = 'flex-shrink-0 w-10 h-10 mr-2';
                                
                                const image = document.createElement('img');
                                if (product.image) {
                                    image.src = `/storage/${product.image}`;
                                } else {
                                    image.src = '/images/placeholder.png';
                                }
                                image.className = 'w-full h-full object-cover';
                                image.alt = product.title;
                                
                                imageContainer.appendChild(image);
                                
                                // Создаем контейнер для информации
                                const infoContainer = document.createElement('div');
                                infoContainer.className = 'flex-1';
                                
                                const title = document.createElement('div');
                                title.className = 'text-sm font-medium';
                                title.textContent = product.title;
                                
                                const details = document.createElement('div');
                                details.className = 'text-xs text-gray-500 flex items-center';
                                
                                const price = document.createElement('span');
                                price.className = 'font-semibold';
                                price.textContent = `$${product.price}`;
                                
                                details.appendChild(price);
                                
                                if (product.category) {
                                    const category = document.createElement('span');
                                    category.className = 'ml-2 px-1.5 py-0.5 bg-gray-200 text-gray-700 rounded-full text-xs';
                                    category.textContent = product.category;
                                    details.appendChild(category);
                                }
                                
                                infoContainer.appendChild(title);
                                infoContainer.appendChild(details);
                                
                                item.appendChild(imageContainer);
                                item.appendChild(infoContainer);
                                
                                item.addEventListener('click', function() {
                                    window.location.href = `/product/${product.slug}`;
                                });
                                
                                suggestionContainer.appendChild(item);
                            });
                        }
                        
                        // Добавляем ссылку для полного поиска
                        const viewAllItem = document.createElement('div');
                        viewAllItem.className = 'p-2 text-center text-amber-500 font-medium hover:bg-gray-100 cursor-pointer border-t';
                        viewAllItem.textContent = `View all results for "${query}"`;
                        viewAllItem.addEventListener('click', function() {
                            document.querySelector('form[action*="search"]').submit();
                        });
                        suggestionContainer.appendChild(viewAllItem);
                        
                        suggestionContainer.classList.remove('hidden');
                    } else {
                        suggestionContainer.innerHTML = '<div class="p-3 text-center text-gray-500">No results found</div>';
                        suggestionContainer.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error fetching search suggestions:', error);
                    suggestionContainer.classList.add('hidden');
                });
        }, 300); // 300ms задержка для дебаунсинга
    });
    
    // Обработка нажатия Enter
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.querySelector('form[action*="search"]').submit();
        }
    });
    
    // Закрытие подсказок при клике вне
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !suggestionContainer.contains(e.target)) {
            suggestionContainer.classList.add('hidden');
        }
    });
} 