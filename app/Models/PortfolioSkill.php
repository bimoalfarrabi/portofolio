<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioSkill extends Model
{
    protected $table = 'portfolio_skills';

    protected $fillable = [
        'name',
        'icon',
        'category',
        'x_position',
        'y_position',
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
