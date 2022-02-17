<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class itemactivivty extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'user_id',
        'status',
        'item_id',
        'quantity'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function item()
    {
        return $this->hasOne(item::class, 'id', 'item_id');
    }
}
