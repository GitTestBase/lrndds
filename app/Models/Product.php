<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    protected $fillable= ['prod_name','category_id'];

    public function setProdNameAttribute($value)
    {
        $this->attributes['prod_name'] = str::ucfirst($value);
    }
}
