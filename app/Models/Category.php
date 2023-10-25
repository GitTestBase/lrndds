<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['category_name','status'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function setCategoryNameAttribute($value)
    {   
        $this->attributes['category_name'] = strtoupper($value);
    }
    public function setStatusAttribute($value)
    {   
        if($value == '')
        {
            $this->attributes['status'] = 1;
        }
        else
        {
            $this->attributes['status'] = $value;
        }
    }
    protected static function boot()
    {
        parent::boot();
        static::saved(function ($category) {
            if (!$category->status) {
                $category->products->each(function ($product) {
                    $product->update(['status' => 0]);
                });
            }
        });
    }
}
