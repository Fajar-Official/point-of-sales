<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'purchases';
    protected $with = ['supplier'];
    function supplier()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }
}
