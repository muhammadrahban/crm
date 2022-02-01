<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'order_ticket',
        'no_item',
        'total_no_item',
        'customer_id',
        'user_id',
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

    /**
     * Get all of the activity for the order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activity()
    {
        return $this->hasMany(activity::class, 'order_id', 'id')->orderBy('created_at', 'DESC');
    }

    /**
     * Get the customer associated with the order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function customer()
    {
        return $this->hasOne(customer::class, 'id', 'customer_id');
    }

    /**
     * Get the user associated with the order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
