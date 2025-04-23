<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductWebController extends Controller
{
public function create()
{
return view('product.create');
}

public function store(Request $request)
{
$validated = $request->validate([
'title' => 'required|string|max:255',
'slug' => 'required|string|unique:products',
'description' => 'nullable|string',
'price' => 'required|numeric',
'image' => 'required|image|max:2048',
]);

$path = $request->file('image')->store('products', 'public');

$validated['image'] = $path;
$validated['image_mime'] = $request->file('image')->getClientMimeType();
$validated['image_size'] = $request->file('image')->getSize();
$validated['created_by'] = Auth::id();
$validated['updated_by'] = Auth::id();
$validated['published'] = true;

Product::create($validated);

return redirect()->route('home')->with('success', 'Product added successfully!');
}
}
