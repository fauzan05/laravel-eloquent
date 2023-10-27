<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Customer extends Model
{
    // use HasFactory;

    protected $table = 'customers';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class,'customer_id', 'id');
                        //wallet (foreign key)      kolom id customer (primary key)
    }

    public function virtualAccount(): HasOneThrough
    {
        // customer -> wallet -> virtual_account
        return $this->hasOneThrough(VirtualAccount::class, Wallet::class,
         'customer_id', // FK on wallets table
         'wallet_id', // FK on virtual_accounts table
         'id', // PK on customers table
         'id' // PK on wallets table
        );
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class,'customer_id', 'id');
    }

    public function likeProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'customers_likes_products', 'customer_id', 'product_id')
                    ->withPivot('created_at')
                    ->using(Like::class);
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class,'imageable');
    }
}
