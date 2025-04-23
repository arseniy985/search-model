@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Add Product</h2>

        @if(session('success'))
            <p style="color: green">{{ session('success') }}</p>
        @endif

        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div>
                <label>Title:</label>
                <input type="text" name="title" required>
            </div>

            <div>
                <label>Slug:</label>
                <input type="text" name="slug" required>
            </div>

            <div>
                <label>Description:</label>
                <textarea name="description"></textarea>
            </div>

            <div>
                <label>Price ($):</label>
                <input type="number" name="price" step="0.01" required>
            </div>

            <div>
                <label>Image:</label>
                <input type="file" name="image" accept="image/*" required>
            </div>

            <button type="submit">Add Product</button>
        </form>
    </div>
@endsection
