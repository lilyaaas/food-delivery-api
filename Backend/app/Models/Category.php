<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'restaurant_id',
        'name'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
