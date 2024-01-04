<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'name',
    ];
    public function tests()
    {
        return $this->belongsToMany(Test::class);
    }
}