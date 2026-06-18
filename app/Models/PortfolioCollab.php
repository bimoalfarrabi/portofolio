<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioCollab extends Model
{
    protected $table = 'portfolio_collab';

    protected $fillable = [
        'email',
        'available',
        'available_label',
        'busy_label',
        'location',
        'time_zone',
        'time_zone_label',
        'response_time',
        'channels',
    ];

    protected function casts(): array
    {
        return [
            'available' => 'boolean',
            'channels' => 'array',
        ];
    }

    public static function current(): self
    {
        return static::query()->orderBy('id')->first()
            ?? static::query()->create([
                'email' => config('portfolio.collab.email'),
                'available' => filter_var(config('portfolio.collab.available'), FILTER_VALIDATE_BOOLEAN),
                'available_label' => config('portfolio.collab.available_label'),
                'busy_label' => config('portfolio.collab.busy_label'),
                'location' => config('portfolio.collab.location'),
                'time_zone' => config('portfolio.collab.time_zone'),
                'time_zone_label' => config('portfolio.collab.time_zone_label'),
                'response_time' => config('portfolio.collab.response_time'),
                'channels' => config('portfolio.collab.channels', []),
            ]);
    }
}
