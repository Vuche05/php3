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
        // Lấy tất cả danh mục và thương hiệu cho các dropdown
        $categories = Category::all();
        $brands = Brand::all();
        
        // Bắt đầu với truy vấn cơ bản cho sản phẩm
        $query = Product::with(['category', 'brand']);
        
        // Áp dụng bộ lọc tìm kiếm nếu được cung cấp
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        // Áp dụng bộ lọc danh mục nếu được cung cấp
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }
        
        // Áp dụng bộ lọc thương hiệu nếu được cung cấp
        if ($request->has('brand') && $request->brand != '') {
            $query->where('brand_id', $request->brand);
        }
        
        // Lấy kết quả phân trang hoặc sản phẩm nổi bật giới hạn
        if ($request->has('search') || $request->has('category') || $request->has('brand')) {
            // Nếu đang tìm kiếm hoặc lọc, sử dụng phân trang
            $featuredProducts = $query->orderBy('created_at', 'desc')
                                      ->paginate(12);
        } else {
            // Nếu xem trang chủ mà không tìm kiếm, chỉ lấy sản phẩm nổi bật
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