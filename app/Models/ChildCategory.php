<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sub_category_id', 'category_id'];

    // Link to SubCategory
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    // Link to Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
