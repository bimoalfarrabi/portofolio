<?php

namespace Database\Seeders;

use App\Models\PortfolioLog;
use App\Models\PortfolioProject;
use App\Models\PortfolioSkill;
use App\Models\PortfolioCollab;
use App\Models\PortfolioStat;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'title' => 'Year Progress',
                'type' => 'open',
                'category' => 'Creative dashboard',
                'year' => '2026',
                'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1200&q=80',
                'description' => 'Dashboard eksperimen untuk melacak progres tahunan dengan komposisi visual yang bersih dan cepat dibaca.',
                'approach' => 'Menyusun data ringkas, motion halus, dan komponen visual yang mudah dipindai tanpa mengorbankan karakter.',
                'stack' => ['Laravel', 'React', 'Motion', 'Tailwind'],
                'outcome' => 'Progress terasa lebih visual, lebih cepat dibaca, dan lebih nyaman dipakai di desktop maupun mobile.',
                'x_position' => '50%',
                'y_position' => '50%',
                'size' => 'size-28 sm:size-32 lg:size-36',
                'accent' => 'bg-zinc-950',
                'sort_order' => 0,
                'is_published' => true,
            ],
            [
                'title' => 'Laravel Notes',
                'type' => 'open',
                'category' => 'Internal tool',
                'year' => '2025',
                'image' => 'https://images.unsplash.com/photo-1516321497487-e288fb19713f?auto=format&fit=crop&w=1200&q=80',
                'description' => 'Catatan internal dengan fokus pada alur kerja yang cepat, ringan, dan minim gesekan.',
                'approach' => 'Memprioritaskan input singkat, hierarki informasi yang jelas, dan navigasi yang tidak mengganggu kerja.',
                'stack' => ['Laravel', 'Inertia', 'Vue', 'MySQL'],
                'outcome' => 'Membantu pencatatan kerja harian jadi lebih konsisten dan lebih mudah dirapikan.',
                'x_position' => '22%',
                'y_position' => '28%',
                'size' => 'size-20 sm:size-24 lg:size-24',
                'accent' => 'bg-zinc-700',
                'sort_order' => 1,
                'is_published' => true,
            ],
            [
                'title' => 'Microsite',
                'type' => 'open',
                'category' => 'Landing page',
                'year' => '2025',
                'image' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1200&q=80',
                'description' => 'Halaman promosi ringkas yang menekankan pesan utama dalam satu lintasan baca.',
                'approach' => 'Meringkas konten menjadi blok pendek, tipografi tegas, dan call-to-action yang langsung terbaca.',
                'stack' => ['React', 'Tailwind', 'Vite'],
                'outcome' => 'Konversi pesan utama jadi lebih jelas dan waktu baca lebih singkat.',
                'x_position' => '77%',
                'y_position' => '28%',
                'size' => 'size-16 sm:size-20 lg:size-20',
                'accent' => 'bg-stone-500',
                'sort_order' => 2,
                'is_published' => true,
            ],
            [
                'title' => 'API Workbench',
                'type' => 'open',
                'category' => 'Backend utility',
                'year' => '2024',
                'image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=1200&q=80',
                'description' => 'Alat internal untuk menguji endpoint, memvalidasi response, dan mempercepat debugging integrasi.',
                'approach' => 'Membuat workflow eksplorasi API yang cepat dengan visual status yang mudah dipahami.',
                'stack' => ['Laravel', 'PHP', 'JSON', 'API'],
                'outcome' => 'Debugging endpoint jadi lebih singkat dan pengujian lebih terstruktur.',
                'x_position' => '20%',
                'y_position' => '68%',
                'size' => 'size-18 sm:size-22 lg:size-22',
                'accent' => 'bg-zinc-600',
                'label_placement' => 'top',
                'sort_order' => 3,
                'is_published' => true,
            ],
            [
                'title' => 'Motion Lab',
                'type' => 'open',
                'category' => 'UI experiment',
                'year' => '2026',
                'image' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?auto=format&fit=crop&w=1200&q=80',
                'description' => 'Eksperimen interaksi untuk mengeksplorasi timing, respons, dan sensasi gerak yang halus.',
                'approach' => 'Fokus pada gestur kecil, easing yang lembut, dan transisi yang terasa organik.',
                'stack' => ['Motion', 'React', 'CSS'],
                'outcome' => 'Interaksi terasa lebih hidup tanpa membuat antarmuka terasa berat.',
                'x_position' => '76%',
                'y_position' => '74%',
                'size' => 'size-18 sm:size-22 lg:size-22',
                'accent' => 'bg-zinc-400',
                'sort_order' => 4,
                'is_published' => true,
            ],

            [
                'title' => 'Client Portal',
                'type' => 'closed',
                'category' => 'Private system',
                'year' => '2026',
                'image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1200&q=80',
                'description' => 'Portal privat untuk kebutuhan klien dengan akses terbatas dan alur kerja yang sangat spesifik.',
                'approach' => 'Membuat interface yang ringkas, aman, dan cukup jelas untuk tim internal maupun stakeholder.',
                'stack' => ['Laravel', 'React', 'Auth', 'Role-based access'],
                'outcome' => 'Akses data lebih terkontrol dan operasional tim jadi lebih efisien.',
                'x_position' => '50%',
                'y_position' => '50%',
                'size' => 'size-28 sm:size-32 lg:size-36',
                'accent' => 'bg-amber-300',
                'sort_order' => 5,
                'is_published' => true,
            ],
            [
                'title' => 'Ops Console',
                'type' => 'closed',
                'category' => 'Internal platform',
                'year' => '2025',
                'image' => 'https://images.unsplash.com/photo-1484417894907-623942c8ee29?auto=format&fit=crop&w=1200&q=80',
                'description' => 'Konsol internal untuk memonitor alur operasional dan mempercepat eksekusi tugas rutin.',
                'approach' => 'Hierarki visual dibuat tegas agar operator bisa membaca status tanpa banyak klik.',
                'stack' => ['Laravel', 'Vue', 'Queues', 'Redis'],
                'outcome' => 'Monitoring lebih cepat dan beban kerja operasional lebih ringan.',
                'x_position' => '22%',
                'y_position' => '30%',
                'size' => 'size-20 sm:size-24 lg:size-24',
                'accent' => 'bg-zinc-200',
                'sort_order' => 6,
                'is_published' => true,
            ],
            [
                'title' => 'Commerce Flow',
                'type' => 'closed',
                'category' => 'Restricted build',
                'year' => '2025',
                'image' => 'https://images.unsplash.com/photo-1556740749-887f6717d7e4?auto=format&fit=crop&w=1200&q=80',
                'description' => 'Workflow transaksi yang dibatasi untuk kebutuhan bisnis tertentu dan jalur pengguna yang jelas.',
                'approach' => 'Memadatkan langkah penting supaya transaksi tetap cepat dan error mudah diisolasi.',
                'stack' => ['React', 'Laravel', 'Payments', 'Tracking'],
                'outcome' => 'Alur transaksi lebih stabil dan lebih mudah dipantau.',
                'x_position' => '76%',
                'y_position' => '28%',
                'size' => 'size-16 sm:size-20 lg:size-20',
                'accent' => 'bg-emerald-300',
                'sort_order' => 7,
                'is_published' => true,
            ],
            [
                'title' => 'Mission Sync',
                'type' => 'closed',
                'category' => 'Automation stack',
                'year' => '2024',
                'image' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=1200&q=80',
                'description' => 'Lapisan otomasi untuk menyinkronkan data dan mengurangi pekerjaan manual antar sistem.',
                'approach' => 'Memakai trigger sederhana dan monitoring yang jelas supaya alur tetap bisa dipercaya.',
                'stack' => ['Laravel', 'Queues', 'Webhooks', 'Cron'],
                'outcome' => 'Sinkronisasi lebih konsisten dan error manual berkurang.',
                'x_position' => '20%',
                'y_position' => '68%',
                'size' => 'size-18 sm:size-22 lg:size-22',
                'accent' => 'bg-sky-300',
                'label_placement' => 'top',
                'sort_order' => 8,
                'is_published' => true,
            ],
            [
                'title' => 'Private Lab',
                'type' => 'closed',
                'category' => 'Client work',
                'year' => '2026',
                'image' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1200&q=80',
                'description' => 'Eksplorasi privat untuk klien yang fokus pada validasi arah desain dan teknologi.',
                'approach' => 'Menyajikan pilihan yang ringkas, terukur, dan mudah dievaluasi oleh tim stakeholder.',
                'stack' => ['React', 'Tailwind', 'Analytics'],
                'outcome' => 'Keputusan desain bisa dibuat lebih cepat dan lebih terarah.',
                'x_position' => '76%',
                'y_position' => '74%',
                'size' => 'size-18 sm:size-22 lg:size-22',
                'accent' => 'bg-stone-200',
                'sort_order' => 9,
                'is_published' => true,
            ]
        ];

        $skills = [
            ['name' => 'Laravel', 'category' => 'backend', 'x_position' => '18%', 'y_position' => '30%', 'sort_order' => 0],
            ['name' => 'PHP', 'category' => 'backend', 'x_position' => '38%', 'y_position' => '18%', 'sort_order' => 1],
            ['name' => 'Vue', 'category' => 'frontend', 'x_position' => '70%', 'y_position' => '26%', 'sort_order' => 2],
            ['name' => 'React', 'category' => 'frontend', 'x_position' => '76%', 'y_position' => '60%', 'sort_order' => 3],
            ['name' => 'Inertia', 'category' => 'frontend', 'x_position' => '43%', 'y_position' => '72%', 'sort_order' => 4],
            ['name' => 'Motion', 'category' => 'ui', 'x_position' => '22%', 'y_position' => '64%', 'sort_order' => 5],
            ['name' => 'Kotlin', 'category' => 'mobile', 'x_position' => '84%', 'y_position' => '42%', 'sort_order' => 6],
        ];

        $logs = [
            ['title' => 'CMS bootstrap', 'logged_at' => '2026-06-09', 'body' => 'Menyiapkan fondasi auth dan struktur admin.', 'tags' => ['CMS', 'Auth', 'Laravel'], 'sort_order' => 0, 'is_published' => true],
            ['title' => 'Orbit cleanup', 'logged_at' => '2026-05-31', 'body' => 'Merapikan transisi project orbit dan modal.', 'tags' => ['UI', 'Motion'], 'sort_order' => 1, 'is_published' => true],
        ];

        $stats = [
            ['key' => 'projects_shipped', 'label' => 'Projects shipped', 'value' => '04', 'note' => 'small, sharp, and finished', 'sort_order' => 0],
            ['key' => 'stack_gravity', 'label' => 'Stack gravity', 'value' => 'Laravel', 'note' => 'PHP-first by default', 'sort_order' => 1],
            ['key' => 'frontend_orbit', 'label' => 'Frontend orbit', 'value' => 'React', 'note' => 'motion-heavy presentation', 'sort_order' => 2],
            ['key' => 'current_signal', 'label' => 'Current signal', 'value' => '2026', 'note' => 'still iterating in public', 'sort_order' => 3],
        ];

        PortfolioProject::query()->delete();
        PortfolioSkill::query()->delete();
        PortfolioLog::query()->delete();
        PortfolioStat::query()->delete();

        foreach ($projects as $project) {
            PortfolioProject::create($project);
        }

        foreach ($skills as $skill) {
            PortfolioSkill::create(array_merge($skill, ['is_active' => true]));
        }

        foreach ($logs as $log) {
            PortfolioLog::create($log);
        }

        foreach ($stats as $stat) {
            PortfolioStat::create(array_merge($stat, ['is_active' => true]));
        }

        if (! PortfolioCollab::query()->exists()) {
            PortfolioCollab::create([
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
}
