<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslation;

class PortfolioStat extends Model
{
    use HasTranslation;
    protected $table = 'portfolio_stats';

    protected $fillable = [
        'key',
        'label',
        'label_en',
        'value',
        'note',
        'note_en',
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
