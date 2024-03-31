<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'limit_now',
        'limit_total',
        'limit_increase',
        'mattruoc',
        'matsau',
        'mattruoc_card',
        'matsau_card',
    ];
}
