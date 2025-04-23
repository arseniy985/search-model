<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $products = Product::query()
            ->where('published', '=', 1)
            ->orderBy('updated_at', 'desc')
            ->paginate(8);
        return view('product.index', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
    
    public function search(Request $request)
    {
        $query = $request->input('query');
        $categoryId = $request->input('category_id');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');

        $productsQuery = Product::query()
            ->where('published', 1);
            
        // Apply search query if provided
        if ($query) {
            $productsQuery->where(function($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%');
            });
        }
        
        // Apply category filter if provided
        if ($categoryId) {
            $productsQuery->where('category_id', $categoryId);
        }
        
        // Apply price filters if provided
        if ($minPrice) {
            $productsQuery->where('price', '>=', $minPrice);
        }
        
        if ($maxPrice) {
            $productsQuery->where('price', '<=', $maxPrice);
        }

        $products = $productsQuery->paginate(8)->withQueryString();
        $categories = Category::all();

        return view('product.index', compact('products', 'query', 'categories', 'categoryId', 'minPrice', 'maxPrice'));
    }

    public function view(Product $product)
    {
        return view('product.view', ['product' => $product]);
    }
}
