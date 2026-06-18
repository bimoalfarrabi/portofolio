<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Collab section
    |--------------------------------------------------------------------------
    |
    | Nilai-nilai yang ditampilkan di section "Let's collab" pada halaman
    | publik. Diatur dari .env supaya bisa diubah tanpa rilis ulang frontend.
    |
    */
    'collab' => [
        'email' => env('PORTFOLIO_COLLAB_EMAIL', 'bimoalfarrabi24@gmail.com'),
        'available' => env('PORTFOLIO_COLLAB_AVAILABLE', true),
        'available_label' => env('PORTFOLIO_COLLAB_AVAILABLE_LABEL', 'Available for new projects'),
        'busy_label' => env('PORTFOLIO_COLLAB_BUSY_LABEL', 'Booked, but still reading messages'),
        'location' => env('PORTFOLIO_COLLAB_LOCATION', 'Indonesia'),
        'time_zone' => env('PORTFOLIO_COLLAB_TIMEZONE', config('app.timezone', 'Asia/Jakarta')),
        'time_zone_label' => env('PORTFOLIO_COLLAB_TIMEZONE_LABEL', 'GMT+7'),
        'response_time' => env('PORTFOLIO_COLLAB_RESPONSE_TIME', 'Usually replies within 24h'),
        'channels' => [
            [
                'label' => 'LinkedIn',
                'href' => env('PORTFOLIO_COLLAB_LINKEDIN', 'https://linkedin.com/in/bimoalfarrabi'),
                'handle' => env('PORTFOLIO_COLLAB_LINKEDIN_HANDLE', 'in/bimoalfarrabi'),
            ],
            [
                'label' => 'GitHub',
                'href' => env('PORTFOLIO_COLLAB_GITHUB', 'https://github.com/bimoalfarrabi'),
                'handle' => env('PORTFOLIO_COLLAB_GITHUB_HANDLE', '@bimoalfarrabi'),
            ],
        ],
    ],
];
