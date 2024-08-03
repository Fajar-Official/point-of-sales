<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profit extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'transaction_id',
        'total'
    ];

    /**
     * transaction
     *
     * @return BelongsTo
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
