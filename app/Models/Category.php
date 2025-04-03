<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'image', 'description', 'parent_id', 'slug'];

    // Mối quan hệ với danh mục cha
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Mối quan hệ với các danh mục con
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Mối quan hệ đệ quy để lấy tất cả danh mục con cháu
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    // Kiểm tra xem danh mục có danh mục con nào không
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    // Lấy các danh mục gốc (danh mục không có danh mục cha)
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    // Lấy tất cả sản phẩm cho danh mục này và các danh mục con cháu
    public function allProducts()
    {
        return $this->hasManyThrough(Product::class, Category::class, 'parent_id', 'category_id');
    }

    // Mối quan hệ sản phẩm thông thường
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    // Lấy đường dẫn đầy đủ của danh mục
    public function getPathAttribute()
    {
        $path = [$this->name];
        $category = $this;
        
        while ($category->parent) {
            $category = $category->parent;
            array_unshift($path, $category->name);
        }
        
        return implode(' > ', $path);
    }
}