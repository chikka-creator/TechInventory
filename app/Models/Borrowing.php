<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'item_id', 
        'borrow_date', 
        'return_date', 
        'status', 
        'condition', 
        'fine'
    ];

    // RELASI: CUKUP DITULIS SATU KALI SAJA DI SINI
    public function user() { 
        return $this->belongsTo(User::class); 
    }

    public function item() { 
        return $this->belongsTo(Item::class); 
    }
}