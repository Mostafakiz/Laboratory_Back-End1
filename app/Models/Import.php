<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory;
    protected $fiilable = [
        'uuid',
        'order_id',
    ];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}