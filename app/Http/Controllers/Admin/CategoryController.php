<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return Inertia::render('Admin/Categories', [
            'categories' => $categories
        ]);
    }

public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:categories,slug',
        'description' => 'nullable|string',
        'is_active' => 'required|boolean',
        'image' => 'nullable|image|max:2048', // 👈 تأكد إن ده مضاف
    ]);

if ($request->hasFile('image')) {
    $image = $request->file('image');
    $imageName = $image->getClientOriginalName(); // 👈 بدون time()

    $destination = public_path('categories');
    $image->move($destination, $imageName);

    $data['image'] = 'categories/' . $imageName;
} else {
    $data['image'] = 'categories/default-category.png';
}




    Category::create($data);

    return redirect('/admin/categories')->with('success', 'Category created successfully!');
}


public function update(Request $request, Category $category)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
        'description' => 'nullable|string',
        'is_active' => 'required|boolean',
        'image' => 'nullable|image|max:2048', // 👈 أضف هذا
    ]);

if ($request->hasFile('image')) {
    $image = $request->file('image');
    $imageName = $image->getClientOriginalName(); // بدون time() حسب طلبك
    $destination = public_path('categories');
    $image->move($destination, $imageName);
    $data['image'] = 'categories/' . $imageName;
} else {
    // استخدم الصورة القديمة كما هي
    $data['image'] = $category->image;
}




    $category->update($data);

    return redirect('/admin/categories')->with('success', 'Category updated successfully!');
}


    public function destroy(Category $category)
    {
        $category->delete();

        return redirect('/admin/categories')->with('success', 'Category deleted successfully!');
    }
}
