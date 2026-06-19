<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslation;

class PortfolioLog extends Model
{
    use HasTranslation;
    protected $table = 'portfolio_logs';

    protected $fillable = [
        'title',
        'title_en',
        'logged_at',
        'body',
        'body_en',
        'tags',
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'logged_at' => 'date',
            'tags' => 'array',
            'is_published' => 'boolean',
        ];
    }
}
