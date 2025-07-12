<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(20); // Add pagination for better performance
        $categories = Category::where('is_active', true)->get();
        return Inertia::render('Admin/Products', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'sku' => 'nullable|string|max:255',
        'brand' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $product = Product::create($data);

    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $idx => $image) {
            $destination = public_path('storage/images/products');
            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $originalName = $image->getClientOriginalName();
            
            $fileName = pathinfo($originalName, PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $counter = 1;
            
            while (file_exists($destination.'/'.$originalName)) {
                $originalName = $fileName.'_'.$counter.'.'.$extension;
                $counter++;
            }

            $image->move($destination, $originalName);

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'storage/images/products/'.$originalName,
                'is_primary' => $idx === 0,
                'alt_text' => $product->name,
            ]);
        }
    }

    return redirect('/admin/products')->with('success', 'Product created successfully!');
}

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'sku' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $product->update($data);

        if ($request->hasFile('images')) {
            if ($request->boolean('replace_images')) {
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }

            foreach ($request->file('images') as $idx => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $idx === 0 && !$product->images()->exists(),
                    'alt_text' => $product->name,
                ]);
            }
        }

        return redirect('/admin/products')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect('/admin/products')->with('success', 'Product deleted successfully!');
    }
}
