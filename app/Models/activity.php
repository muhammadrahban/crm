<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class activity extends Model
{
    use HasFactory;
    use DateTimeInterface;

    protected $fillable = [
        'order_id',
        'user_id',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
    */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d g:i a');
    }

    /**
     * Get the user associated with the activity
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
