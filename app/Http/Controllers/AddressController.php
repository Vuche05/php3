<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;

class AddressController extends Controller
{
    public function getProvinces()
    {
        // Trong thực tế, bạn nên lấy dữ liệu từ database
        // Đây là dữ liệu mẫu
        $provinces = [
            ['id' => 1, 'name' => 'Thành phố Hà Nội'],
            ['id' => 2, 'name' => 'Thành phố Hồ Chí Minh'],
            ['id' => 3, 'name' => 'Thành phố Đà Nẵng'],
            ['id' => 4, 'name' => 'Thành phố Hải Phòng'],
            ['id' => 5, 'name' => 'Tỉnh Bình Dương'],
            ['id' => 6, 'name' => 'Tỉnh Đồng Nai'],
            // Thêm các tỉnh thành khác nếu cần
        ];
        
        return response()->json($provinces);
    }
    
    public function getDistricts(Request $request)
    {
        $province = $request->query('province');
        
        // Trong thực tế, bạn nên lấy dữ liệu từ database dựa vào tỉnh được chọn
        // Đây là dữ liệu mẫu
        $districts = [];
        
        if ($province == 'Thành phố Hà Nội') {
            $districts = [
                ['id' => 1, 'name' => 'Quận Ba Đình'],
                ['id' => 2, 'name' => 'Quận Hoàn Kiếm'],
                ['id' => 3, 'name' => 'Quận Hai Bà Trưng'],
                ['id' => 4, 'name' => 'Quận Đống Đa'],
                ['id' => 5, 'name' => 'Quận Cầu Giấy'],
                // Thêm các quận huyện khác nếu cần
            ];
        } elseif ($province == 'Thành phố Hồ Chí Minh') {
            $districts = [
                ['id' => 1, 'name' => 'Quận 1'],
                ['id' => 2, 'name' => 'Quận 3'],
                ['id' => 3, 'name' => 'Quận 4'],
                ['id' => 4, 'name' => 'Quận 5'],
                ['id' => 5, 'name' => 'Quận 6'],
                // Thêm các quận huyện khác nếu cần
            ];
        }
        
        return response()->json($districts);
    }
    
    public function getWards(Request $request)
    {
        $province = $request->query('province');
        $district = $request->query('district');
        
        // Trong thực tế, bạn nên lấy dữ liệu từ database dựa vào quận được chọn
        // Đây là dữ liệu mẫu
        $wards = [];
        
        if ($province == 'Thành phố Hà Nội' && $district == 'Quận Ba Đình') {
            $wards = [
                ['id' => 1, 'name' => 'Phường Phúc Xá'],
                ['id' => 2, 'name' => 'Phường Trúc Bạch'],
                ['id' => 3, 'name' => 'Phường Vĩnh Phúc'],
                ['id' => 4, 'name' => 'Phường Cống Vị'],
                ['id' => 5, 'name' => 'Phường Liễu Giai'],
                // Thêm các phường khác nếu cần
            ];
        } elseif ($province == 'Thành phố Hồ Chí Minh' && $district == 'Quận 1') {
            $wards = [
                ['id' => 1, 'name' => 'Phường Bến Nghé'],
                ['id' => 2, 'name' => 'Phường Bến Thành'],
                ['id' => 3, 'name' => 'Phường Cầu Kho'],
                ['id' => 4, 'name' => 'Phường Cầu Ông Lãnh'],
                ['id' => 5, 'name' => 'Phường Cô Giang'],
                // Thêm các phường khác nếu cần
            ];
        }
        
        return response()->json($wards);
    }
}