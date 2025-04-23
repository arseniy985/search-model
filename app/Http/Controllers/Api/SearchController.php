<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Get search suggestions for products
     */
    public function suggestions(Request $request)
    {
        $query = $request->input('query');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        // Поиск товаров
        $products = Product::query()
            ->where('published', 1)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%');
            })
            ->select('id', 'title', 'slug', 'image', 'price', 'category_id')
            ->with('category:id,name')
            ->limit(5)
            ->get();
            
        $results = [];
        
        // Форматируем результаты товаров
        foreach ($products as $product) {
            $results[] = [
                'id' => $product->id,
                'title' => $product->title,
                'slug' => $product->slug,
                'image' => $product->image,
                'price' => $product->price,
                'type' => 'product',
                'category' => $product->category ? $product->category->name : null
            ];
        }
        
        // Поиск категорий
        $categories = Category::query()
            ->where('name', 'like', '%' . $query . '%')
            ->select('id', 'name')
            ->limit(3)
            ->get();
            
        // Добавляем категории в результаты
        foreach ($categories as $category) {
            $results[] = [
                'id' => $category->id,
                'title' => $category->name,
                'type' => 'category'
            ];
        }
        
        return response()->json($results);
    }
}
