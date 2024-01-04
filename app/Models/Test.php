<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'name',
        'type',
        'price',
        'admin_id',
    ];
    public function User()
    {
        return $this->belongsTo(User::class);
    }
    public function devices()
    {
        return $this->belongsToMany(Device::class);
    }
}