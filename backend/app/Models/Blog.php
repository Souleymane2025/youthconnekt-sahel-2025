<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','slug','category','excerpt','content','status','published_at','author_name'
    ];

    protected $casts = [
        'published_at' => 'datetime'
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
