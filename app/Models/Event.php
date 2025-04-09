<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $primaryKey = 'event_id';

    protected $fillable = [
        'title',
        'description',
        'location',
        'date',
        'thumbnail',
        'author'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'author');
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'author');
    }
}
