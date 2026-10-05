<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TickerItem extends Model
{
    protected $fillable = ['title', 'item_date', 'is_active'];

    protected $casts = ['item_date' => 'date', 'is_active' => 'boolean'];

    public function scopeShown($query)
    {
        return $query->where('is_active', true);
    }
}
