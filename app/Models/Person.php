<?php

namespace App\Models;

use App\Casts\AsAddress;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'persons';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = true;

    //$cast untuk merubah tipe data secara otomatis dari tipe data didatabase,
    //dengan tipe data yang ada di PHP
    protected $casts = [
        "address" => AsAddress::class,
        "created_at" => 'datetime',
        "updated_at" => 'datetime'
    ];

    //accessors dan mutators
    protected function fullName() : Attribute // memanggil nya menggunakan full_name
    {
        return Attribute::make(
            get: function () : string {
                return $this -> first_name . ' ' . $this->last_name;
            },
            set: function (string $value) : array
            {
                $names = explode(' ', $value);
                return [
                    "first_name" => $names[0],
                    "last_name" => $names[1] ?? ''
                ];
            }

        );
    }

    //sama dengan nama kolomnya
    protected function firstName() : Attribute
    {
        return Attribute::make(
            get: function ($values, $attribute): string {
                return strtoupper($values);
            },
            set: function ($values): array {
                return[
                    'first_name' => strtoupper($values)
                ];
            }
        );
    }


}


