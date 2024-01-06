<?php

namespace App\Models;

use App\Models\Scopes\IsActiveScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $keyType='string';
    public $incrementing = false;
    public $timestamps = false;

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
}
