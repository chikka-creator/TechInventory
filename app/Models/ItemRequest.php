<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'item_name', 
        'category', 
        'reason', 
        'status'
    ];

    // RELASI: PASTIKAN HANYA ADA SATU FUNGSI USER() DI SINI
    public function user() 
    { 
        return $this->belongsTo(User::class); 
    }
}