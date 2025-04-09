<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{

    protected $primaryKey = 'course_id';

    protected $fillable = [
        'course_title',
        'description',
        'thumbnail',
        'author',
        'category_id',
        'price',
        'vidio_link'
    ];


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'author');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(CourseVideo::class, 'course_id');
    }
}
