<?php

namespace App\Models;

use App\Models\Scopes\IsActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $keyType='string';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts= [
        //Dokumentasi Date Format di https://www.php.net/manual/en/datetime.format.php
        'created_at' => 'datetime:U'
    ];

    // jadi secara bawaan attribute di model tidak bisa di set secara massal menggunakan method create()
    // jadi kita harus memberitahu attribute mana saja yang bisa diubah dengan menggunakan attribute $fillable di modelnya

    protected $fillable = [
        'id',
        'name',
        'description'
    ];

    //override boot untuk menambahkan globalscope

    protected static function booted():void
    {
        parent::booted();
        self::addGlobalScope(new IsActiveScope());
    }

    public function products() : HasMany
    {
        return $this->hasMany(Product::class,'category_id','id');
    }

    //mengambil 1 nilai
    public function cheapestProduct() : HasOne
    {
        return $this->hasOne(Product::class,'category_id','id')->oldest("price");
    }
    public function mostExpensiveProduct() : HasOne
    {
        return $this->hasOne(Product::class,'category_id','id')->latest("price");
    }

    public function review() : HasManyThrough
    {
        return $this->hasManyThrough(Review::class,Product::class,
            "category_id", "product_id","id","id");
    }
}
