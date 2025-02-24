<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;

    public function product(): BelongsTo
    {  
        return $this->belongsTo(Product::class,'product_id', 'id'); 
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class,'customer_id', 'id');
    }

}
