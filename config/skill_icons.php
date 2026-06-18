<?php

/*
|--------------------------------------------------------------------------
| Skill icon registry (CMS-facing)
|--------------------------------------------------------------------------
|
| Daftar kunci ikon yang tersedia di komponen frontend `BrandIcon`.
| Dipakai untuk dropdown di form CMS dan validasi. HARUS selaras dengan
| objek ICONS di resources/js/components/shared/BrandIcon.jsx.
|
| Grouping hanya untuk tampilan (optgroup) di form.
|
*/

return [
    'groups' => [
        'Brand' => ['react', 'vue', 'nextjs', 'tailwind', 'vite', 'kotlin', 'figma'],
        'Languages' => ['typescript', 'javascript', 'php', 'python'],
        'Stack' => ['laravel', 'nodejs', 'motion', 'inertia', 'graphql'],
        'Data' => ['mysql', 'postgresql', 'mongodb', 'redis'],
        'Tooling' => ['docker', 'git'],
        'Concept' => ['auth', 'json', 'api', 'css', 'queues', 'webhooks', 'cron', 'analytics', 'payments', 'tracking', 'rbac'],
        'Social' => ['email', 'linkedin', 'github'],
    ],
];
