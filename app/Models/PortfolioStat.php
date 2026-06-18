<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioStat extends Model
{
    protected $table = 'portfolio_stats';

    protected $fillable = [
        'key',
        'label',
        'value',
        'note',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
