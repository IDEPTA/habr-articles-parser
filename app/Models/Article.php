<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Article extends Model
{
    use Searchable;

    protected $fillable = [
        'title',
        'content',
        'source',
        'url',
        'category',
        'tags',
        'published_at'
    ];

    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title ?? '',
            'content' => $this->content ?? '',
            'category' => $this->category ?? '',
        ];
    }
}
