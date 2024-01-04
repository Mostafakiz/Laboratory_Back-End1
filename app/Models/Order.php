<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'nurse_id',
        'patient_id',
        'doctor_id',
        'test_id',
        'date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function bills()
    {
        return $this->belongsToMany(Bill::class);
    }
    public function imports()
    {
        return $this->hasMany(Import::class);
    }
}