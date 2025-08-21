<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    //
    use HasFactory;

    protected $casts = [
        'publish_at' => 'datetime',
    ];

    protected $fillable = ['title', 'description', 'category_id', 'publish_at'];


    public function event_images()
    {
        return $this->hasMany(EventImage::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
