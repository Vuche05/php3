<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    // Hiển thị danh sách tất cả các thương hiệu
    public function index()
    {
        $brands = Brand::all(); // Lấy dữ liệu từ cơ sở dữ liệu
        return view('brands.index', compact('brands')); // Trả về view với dữ liệu
    }

    
    // Hiển thị form thêm mới thương hiệu
    public function create()
    {
        return view('brands.create');
    }

    // Lưu thương hiệu mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:brands|max:255',
        ]);

        Brand::create([
            'name' => $request->name,
        ]);

        return redirect()->route('brands.index');
    }

    // Hiển thị form chỉnh sửa thương hiệu
    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('brands.edit', compact('brand'));
    }

    // Cập nhật thông tin thương hiệu
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $brand = Brand::findOrFail($id);
        $brand->update([
            'name' => $request->name,
        ]);

        return redirect()->route('brands.index');
    }

    // Xóa thương hiệu
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        return redirect()->route('brands.index');
    }
}
