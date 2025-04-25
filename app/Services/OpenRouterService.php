<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class OpenRouterService implements AIServiceInterface
{
    private string $apiKey;
    private string $baseUrl = 'https://openrouter.ai/api/v1';
    private string $defaultModel = 'meta-llama/llama-4-scout:free';

    /**
     * Конструктор сервиса Open Router
     *
     * @param string $apiKey API ключ для доступа к Open Router
     */
    public function __construct(string $apiKey = null)
    {
        $this->apiKey = $apiKey ?? config('services.openrouter.key');
    }

    /**
     * Отправляет запрос к Open Router и получает ответ
     *
     * @param string $context Контекст/системный промпт для нейросети
     * @param string $text Текст запроса пользователя
     * @param mixed $model Модель нейросети (по умолчанию meta-llama/llama-4-scout:free)
     * @return string Ответ от нейросети
     * @throws RequestException
     */
    public function query(string $context, string $text, mixed $model = null): string
    {
        if (!$model) {
            $model = $this->defaultModel;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'HTTP-Referer' => config('app.url'),
                'X-Title' => config('app.name'),
            ])->post("{$this->baseUrl}/chat/completions", [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $context
                    ],
                    [
                        'role' => 'user',
                        'content' => $text
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? 'Нет ответа от нейросети';
            } else {

                return "Ошибка при обращении к API: {$response->status()} - " .
                       ($response->json()['error']['message'] ?? 'Неизвестная ошибка');
            }
        } catch (\Exception $e) {
            

            return "Произошла ошибка при обращении к нейросети: {$e->getMessage()}";
        }
    }

    /**
     * Поиск товаров на основе запроса пользователя
     *
     * @param string $userQuery Запрос пользователя
     * @return array Массив с ответом AI и найденными товарами
     */
    public function searchProducts(string $userQuery): array
    {
        // Получаем все товары из базы данных
        $products = $this->getAllProducts();
        
        // Формируем контекст с информацией о товарах
        $productContext = $this->formatProductsForContext($products);
        
        // Создаем системный промпт
        $systemPrompt = $this->buildSystemPrompt($productContext, $products->count());
        
        // Отправляем запрос к нейросети
        $aiResponse = $this->query($systemPrompt, $userQuery);
        
        // Извлекаем ID товаров, упомянутых в ответе
        $mentionedProductIds = $this->extractProductIds($aiResponse);
        
        // Фильтруем товары, которые были упомянуты в ответе
        $mentionedProducts = $products->filter(function ($product) use ($mentionedProductIds) {
            return in_array($product->id, $mentionedProductIds);
        });
        
        // Если нет упомянутых товаров, вернем первые 5 товаров для отображения
        $productsToShow = $mentionedProducts->isEmpty() ? $products->take(5) : $mentionedProducts;
        
        return [
            'response' => $aiResponse,
            'products' => $productsToShow
        ];
    }
    
    /**
     * Извлекает ID товаров, упомянутых в ответе AI
     *
     * @param string $response Ответ от AI
     * @return array Массив ID товаров
     */
    protected function extractProductIds(string $response): array
    {
        preg_match_all('/\[ID:(\d+)\]/', $response, $matches);
        
        if (!empty($matches[1])) {
            return array_map('intval', $matches[1]);
        }
        
        return [];
    }

    /**
     * Получить все опубликованные товары из базы данных
     *
     * @return Collection Коллекция товаров
     */
    protected function getAllProducts(): Collection
    {
        return Product::query()
            ->where('published', true)
            ->with('category') // Подгружаем категории сразу
            ->get();
    }

    /**
     * Форматирует коллекцию товаров в текстовый контекст для AI
     *
     * @param Collection $products Коллекция товаров
     * @return string Отформатированный контекст
     */
    protected function formatProductsForContext(Collection $products): string
    {
        if ($products->isEmpty()) {
            return "В нашем магазине нет товаров.";
        }

        // Если товаров слишком много, предоставляем сводку
        if ($products->count() > 30) {
            $categorySummary = $products->groupBy(function ($product) {
                return $product->category?->name ?? 'Без категории';
            })->map(function ($products, $category) {
                return sprintf("%s (%d товаров)", $category, $products->count());
            })->join(", ");
            
            // Выбираем разнообразную выборку товаров для демонстрации
            $sampleProducts = $products->random(min(20, $products->count()));
            
            $formattedProducts = $sampleProducts->map(function ($product, $index) {
                return sprintf(
                    "[Товар %d]\nID: %d\nНазвание: %s\nКатегория: %s\nЦена: $%.2f\nОписание: %s",
                    $index + 1,
                    $product->id,
                    $product->title,
                    $product->category?->name ?? 'Без категории',
                    $product->price,
                    Str::limit($product->description, 100)
                );
            })->join("\n\n");
            
            return "В нашем магазине " . $products->count() . " товаров в следующих категориях: " . $categorySummary . ".\n\nВот примеры наших товаров:\n\n" . $formattedProducts;
        }
        
        // Если количество товаров разумное, включаем все
        $formattedProducts = $products->map(function ($product, $index) {
            return sprintf(
                "[Товар %d]\nID: %d\nНазвание: %s\nКатегория: %s\nЦена: $%.2f\nОписание: %s",
                $index + 1,
                $product->id,
                $product->title,
                $product->category?->name ?? 'Без категории',
                $product->price,
                Str::limit($product->description, 100)
            );
        })->join("\n\n");

        return $formattedProducts;
    }

    /**
     * Создает системный промпт с инструкциями и контекстом товаров
     *
     * @param string $productContext Контекст с информацией о товарах
     * @param int $productCount Количество товаров
     * @return string Системный промпт
     */
    protected function buildSystemPrompt(string $productContext, int $productCount): string
    {
        return <<<EOT
Ты - полезный ассистент для интернет-магазина HyperX Store. Твоя задача - помогать клиентам находить товары из нашего каталога.

В нашем магазине {$productCount} товаров. Основываясь на запросе пользователя, рекомендуй наиболее подходящие товары из нашего каталога. Вот информация о наших товарах:

{$productContext}

На основе вопроса клиента порекомендуй наиболее подходящие товары из нашего каталога. Будь кратким и полезным.

ВАЖНО: Когда рекомендуешь товар, всегда включай ID товара в таком формате: [ID:123], где 123 - это ID товара. Это будет использовано для создания ссылок.

ФОРМАТИРОВАНИЕ: Всегда форматируй свой ответ с использованием Markdown:
- Используй **жирный текст** для названий товаров
- Используй *курсив* для выделения важной информации
- Используй списки для перечисления преимуществ товаров
- Используй заголовки для структурирования ответа
- Используй эмодзи для более дружелюбного общения

Помни:
1. Будь дружелюбным и полезным
2. Будь кратким - используй 2-3 предложения на каждый товар
3. Всегда включай ID товаров в формате [ID:123] при упоминании конкретного товара
4. Рекомендуй только товары из нашего каталога
5. Если нет подходящих товаров, предложи близкие альтернативы
EOT;
    }
} 