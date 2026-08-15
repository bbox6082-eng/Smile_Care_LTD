<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Doctor extends Model
{
    protected $fillable = [
        'name',
        'chamber_name',
        'mobile_number',
        'email',
        'note',
        'marketing_representative_name',
        'territory_id',
        'chamber_address',
        'chamber_map_link',
        'created_by',
        'mr_assigned_at',
    ];

    protected $casts = [
        'mr_assigned_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function territory(): BelongsTo
    {
        return $this->belongsTo(Territory::class);
    }
}
