<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'phone',
        'address',
        'location',
        'longitude',
        'latitude',
        'chat_id',
        'status'
    ];

}
