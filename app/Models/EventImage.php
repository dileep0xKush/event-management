<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class EventImage extends Model
{

    use HasFactory;

    protected $fillable = ['photo_path', 'event_id'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
