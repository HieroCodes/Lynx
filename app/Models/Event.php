<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        // Add other fields here
    ];

    // Define any relationships, if applicable
    // For example, if an event belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
