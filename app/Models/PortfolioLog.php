<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioLog extends Model
{
    protected $table = 'portfolio_logs';

    protected $fillable = [
        'title',
        'logged_at',
        'body',
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
