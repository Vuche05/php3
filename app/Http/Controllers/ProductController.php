<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand', 'images'])
            ->latest()
            ->paginate(10);

        return view('products.index', compact('products'));
    }
    
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'description' => 'nullable|string',
            'discount' => 'nullable|numeric|min:0|max:100',
            'primary_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
    
        // Tạo sản phẩm
        $product = Product::create([
            'name' => $validatedData['name'],
            'price' => $validatedData['price'],
            'quantity' => $validatedData['quantity'],
            'category_id' => $validatedData['category_id'],
            'brand_id' => $validatedData['brand_id'],
            'description' => $validatedData['description'] ?? null,
            'discount' => $validatedData['discount'] ?? 0,
        ]);
    
        // Lưu trữ hình ảnh chính
        if ($request->hasFile('primary_image')) {
            $imagePath = $request->file('primary_image')->store('products', 'public');
            
            // Lưu vào trường legacy để tương thích ngược
            $product->update(['image' => $imagePath]);
            
            // Tạo bản ghi ProductImage
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $imagePath,
                'is_primary' => true,
                'display_order' => 0
            ]);
        }

        // Lưu trữ các hình ảnh bổ sung
        if ($request->hasFile('additional_images')) {
            $order = 1;
            foreach ($request->file('additional_images') as $image) {
                $imagePath = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'is_primary' => false,
                    'display_order' => $order++
                ]);
            }
        }
    
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được thêm!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'description' => 'nullable|string',
            'discount' => 'nullable|numeric|min:0|max:100',
            'primary_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_image_ids' => 'nullable|array',
            'remove_image_ids.*' => 'exists:product_images,id'
        ]);

        // Cập nhật thông tin sản phẩm
        $product->update([
            'name' => $validatedData['name'],
            'price' => $validatedData['price'],
            'quantity' => $validatedData['quantity'],
            'category_id' => $validatedData['category_id'],
            'brand_id' => $validatedData['brand_id'],
            'description' => $validatedData['description'] ?? null,
            'discount' => $validatedData['discount'] ?? 0,
        ]);

        // Cập nhật hình ảnh chính nếu được cung cấp
        if ($request->hasFile('primary_image')) {
            $imagePath = $request->file('primary_image')->store('products', 'public');
            
            // Cập nhật trường hình ảnh legacy
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->update(['image' => $imagePath]);
            
            // Tìm hình ảnh chính hiện có hoặc tạo mới
            $primaryImage = $product->images()->where('is_primary', true)->first();
            if ($primaryImage) {
                // Xóa tệp cũ
                Storage::disk('public')->delete($primaryImage->image_path);
                // Cập nhật bản ghi
                $primaryImage->update(['image_path' => $imagePath]);
            } else {
                // Tạo hình ảnh chính mới
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'is_primary' => true,
                    'display_order' => 0
                ]);
            }
        }

        // Thêm hình ảnh bổ sung mới
        if ($request->hasFile('additional_images')) {
            $maxOrder = $product->images()->where('is_primary', false)->max('display_order') ?? 0;
            $order = $maxOrder + 1;
            
            foreach ($request->file('additional_images') as $image) {
                $imagePath = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'is_primary' => false,
                    'display_order' => $order++
                ]);
            }
        }

        // Xóa hình ảnh nếu được yêu cầu
        if ($request->has('remove_image_ids') && is_array($request->remove_image_ids)) {
            $imagesToRemove = ProductImage::where('product_id', $product->id)
                ->whereIn('id', $request->remove_image_ids)
                ->get();
                
            foreach ($imagesToRemove as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }

        return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function userProducts(Request $request)
    {
        $query = Product::query()->with(['category', 'brand', 'images']);
    
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
    
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('brand') && !empty($request->brand)) {
            $query->where('brand_id', $request->brand);
        }
    
        if ($request->has('min_price') && !empty($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }
    
        if ($request->has('max_price') && !empty($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }
    
        $products = $query->paginate(4);
    
        $categories = Category::all();
        $brands = Brand::all();
    
        return view('products.products', compact('products', 'categories', 'brands'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'images']);
        $relatedProducts = Product::whereHas('category', function($query) use ($product) {
            $query->where('slug', $product->category->slug);
        })
            ->where('id', '!=', $product->id)
            ->with('images')
            ->inRandomOrder()
            ->limit(4)
            ->get();
        
        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function destroy(Product $product)
    {
        // Xóa tất cả hình ảnh sản phẩm từ kho lưu trữ
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        // Xóa sản phẩm (sẽ xóa theo dạng cascade các hình ảnh liên quan)
        $product->delete();
        
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được xóa!');
    }
}