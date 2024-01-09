<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
  protected $table = 'products';
  protected $primaryKey = 'id';
  protected $keyType = 'string';
  public $incrementing = false;
  public $timestamps = false;


  //tidak akan ditampilkan saat serialization
  protected $hidden = [
      'category_id'
  ];

  public function category() : BelongsTo
  {
    return $this->belongsTo(Category::class,'category_id','id');
  }

    public function review() : HasMany
    {
        return $this->hasMany(Review::class,"customer_id","id");
    }

    public function likedByCustomer() : BelongsToMany
    {
        return $this->belongsToMany(Customer::class,'customers_likes_products','product_id','customer_id')
            ->withPivot("created_at")
            ->using(Like::class);
    }


}
