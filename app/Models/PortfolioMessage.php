<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioMessage extends Model
{
    protected $table = 'portfolio_messages';

    protected $fillable = [
        'name',
        'email',
        'message',
        'ip_address',
        'is_read',
        'mail_sent',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'mail_sent' => 'boolean',
        ];
    }
}
