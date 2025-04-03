<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller {
    public function index() {
        $rootCategories = Category::root()->with('children')->latest()->get();
        $allCategories = Category::all();
        return view('categories.index', compact('rootCategories', 'allCategories'));
    }

    public function create() {
        $categories = Category::all();
        return view('categories.create', compact('categories'));
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string|max:500',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        Category::create([
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['name']),
            'parent_id' => $validatedData['parent_id'],
            'image' => $imagePath,
            'description' => $validatedData['description'] ?? null,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Danh mục đã được tạo thành công!');
    }

    public function edit(Category $category) {
        $parentCategories = Category::where('id', '!=', $category->id)->get(); 
        return view('categories.edit', compact('category', 'parentCategories'));
    }
    

    public function update(Request $request, Category $category) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validatedData['parent_id'] == $category->id) {
            return back()->withErrors(['parent_id' => 'Danh mục không thể là cha của chính nó.']);
        }

        // Kiểm tra xem cha được chọn không phải là con của danh mục này
        if ($validatedData['parent_id']) {
            $parentCategory = Category::find($validatedData['parent_id']);
            $ancestor = $parentCategory;
            
            while ($ancestor) {
                if ($ancestor->id == $category->id) {
                    return back()->withErrors(['parent_id' => 'Không thể chọn danh mục con làm cha.']);
                }
                $ancestor = $ancestor->parent;
            }
        }

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
            'slug' => Str::slug($validatedData['name']),
            'parent_id' => $validatedData['parent_id'],
            'image' => $imagePath,
            'description' => $validatedData['description'] ?? null,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Danh mục đã được cập nhật thành công!');
    }

    public function destroy(Category $category) {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        // Gán lại các danh mục con cho danh mục cha của danh mục này
        if ($category->children()->count() > 0) {
            foreach ($category->children as $child) {
                $child->update(['parent_id' => $category->parent_id]);
            }
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Danh mục đã được xóa thành công!');
    }
}