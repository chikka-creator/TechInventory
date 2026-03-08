<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'category', 'stock', 'description'];
    
    public function borrowings() {
        return $this->hasMany(Borrowing::class);
    }
}