<?php

namespace App\Models;

use Laravel\Scout\Builder;
use Laravel\Scout\Searchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

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

    // Доделать поиск по нескольким моделям, поиск по не полным словам
    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title ?? '',
            // 'content' => $this->content ?? '',
            'category' => is_array($this->category)
                ? implode(', ', $this->category)
                : (string) $this->category,
        ];
    }
}
