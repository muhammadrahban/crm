<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'customer_name',
        'carteen_no',
        'cargo_id',
        'remarks',
        'order_status',
    ];

    /**
     * Get all of the items for the order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(item::class, 'order_id', 'id');
    }

    /**
     * Get the cargo associated with the order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cargo()
    {
        return $this->hasOne(cargo::class, 'id', 'cargo_id');
    }
}
