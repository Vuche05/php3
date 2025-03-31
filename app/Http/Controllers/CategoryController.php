<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller {
    public function index() {
        $categories = Category::latest()->get();
        return view('categories.index', compact('categories'));
    }

    public function create() {
        return view('categories.create');
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string|max:500',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        Category::create([
            'name' => $validatedData['name'],
            'image' => $imagePath,
            'description' => $validatedData['description'] ?? null,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Danh mục đã được tạo thành công!');
    }

    public function edit(Category $category) {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string|max:500',
        ]);

        $imagePath = $category->image;
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            // Lưu ảnh mới
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        $category->update([
            'name' => $validatedData['name'],
            'image' => $imagePath,
            'description' => $validatedData['description'] ?? null,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Danh mục đã được cập nhật thành công!');
    }

    public function destroy(Category $category) {
        // Xóa ảnh nếu tồn tại
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        // Xóa category
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Danh mục đã được xóa thành công!');
    }
}