<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'image',
        'name',
        'description'
    ];

    /**
     * products
     *
     * @return HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
