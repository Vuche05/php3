<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller {
    public function index(Request $request) {
        // Get all categories and brands for the dropdowns
        $categories = Category::all();
        $brands = Brand::all();
        
        // Start with a base query for products
        $query = Product::with(['category', 'brand']);
        
        // Apply search filter if provided
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        // Apply category filter if provided
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }
        
        // Apply brand filter if provided
        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand_id', $request->brand);
        }
        
        // Get paginated results or limited featured products
        if ($request->has('search') || $request->has('category') || $request->has('brand')) {
            // If searching or filtering, use pagination
            $featuredProducts = $query->orderBy('created_at', 'desc')
                                      ->paginate(12);
        } else {
            // If homepage view without search, just get featured products
            $featuredProducts = $query->orderBy('created_at', 'desc')
                                      ->take(8)
                                      ->paginate(8);
        }
        
        return view('index', compact('featuredProducts', 'categories', 'brands'));
    }

    // public function index()
    // {
    //     return view('home'); // Hoặc view nào bạn muốn trả về
    // }
}