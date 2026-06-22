/**
 * BrandIcon — official brand marks via simple-icons, plus hand-drawn
 * concept glyphs for non-brand tags.
 *
 *  - Brand marks: pulled from `simple-icons` (CC0, official single-path,
 *    viewBox 0 0 24 24). Always accurate, easy to extend.
 *  - Concept icons: hand-drawn Lucide-style for abstract tags that have no
 *    brand (Auth, JSON, API, Queues, etc.) plus Email and Motion/Inertia.
 *  - Fallback: bracketed mono monogram, on-theme with the engineering vibe.
 *
 * Everything renders at the parent's font color (`currentColor`) and the
 * size passed via `className`.
 */
import {
    siReact,
    siVuedotjs,
    siTailwindcss,
    siVite,
    siKotlin,
    siLaravel,
    siPhp,
    siMysql,
    siRedis,
    siNodedotjs,
    siTypescript,
    siJavascript,
    siPython,
    siDocker,
    siGit,
    siFigma,
    siNextdotjs,
    siPostgresql,
    siMongodb,
    siGraphql,
    siGithub,
    siAlpinedotjs,
    siAndroid,
    siAndroidstudio,
    siCodeigniter,
    siCpanel,
    siHtml5,
    siCss,
    siBootstrap,
    siLinux,
    siJetpackcompose,
    siArchlinux,
    siSvelte,
} from 'simple-icons';

// ── Brand marks (official paths) ───────────────────────────────────────
const BRAND_PATHS = {
    react: siReact.path,
    vue: siVuedotjs.path,
    tailwind: siTailwindcss.path,
    vite: siVite.path,
    kotlin: siKotlin.path,
    laravel: siLaravel.path,
    php: siPhp.path,
    mysql: siMysql.path,
    redis: siRedis.path,
    nodejs: siNodedotjs.path,
    typescript: siTypescript.path,
    javascript: siJavascript.path,
    python: siPython.path,
    docker: siDocker.path,
    git: siGit.path,
    figma: siFigma.path,
    nextjs: siNextdotjs.path,
    postgresql: siPostgresql.path,
    mongodb: siMongodb.path,
    graphql: siGraphql.path,
    github: siGithub.path,
    alpinejs: siAlpinedotjs.path,
    android: siAndroid.path,
    androidstudio: siAndroidstudio.path,
    codeigniter: siCodeigniter.path,
    cpanel: siCpanel.path,
    html: siHtml5.path,
    css: siCss.path,
    bootstrap: siBootstrap.path,
    linux: siLinux.path,
    jetpackcompose: siJetpackcompose.path,
    archlinux: siArchlinux.path,
    svelte: siSvelte.path,
};

// ── Concept icons (hand-drawn; no brand exists) ────────────────────────
const CONCEPT_ICONS = {
    motion: (
        <g fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round">
            <path d="M2 13 Q 5.5 6, 9 13 T 16 13" />
            <circle cx="20" cy="13" r="1.7" fill="currentColor" stroke="none" />
        </g>
    ),
    inertia: (
        <g fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinejoin="miter">
            <path d="M3 18 L9 12 L13 16 L21 6" />
            <path d="M21 6 L21 11 M21 6 L16 6" />
        </g>
    ),
    auth: (
        <g fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round">
            <rect x="4" y="11" width="16" height="10" rx="1" />
            <path d="M8 11 V7 a4 4 0 0 1 8 0 V11" />
        </g>
    ),
    json: (
        <g fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round">
            <path d="M9 4 H7 a2 2 0 0 0 -2 2 v3 a2 2 0 0 1 -2 2 a2 2 0 0 1 2 2 v3 a2 2 0 0 0 2 2 h2" />
            <path d="M15 4 h2 a2 2 0 0 1 2 2 v3 a2 2 0 0 0 2 2 a2 2 0 0 0 -2 2 v3 a2 2 0 0 1 -2 2 h-2" />
        </g>
    ),
    api: (
        <g fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round">
            <path d="M8 6 L2 12 L8 18" />
            <path d="M16 6 L22 12 L16 18" />
            <path d="M14 4 L10 20" />
        </g>
    ),
    css: (
        <g fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round">
            <path d="M5 9 H21 M3 15 H19 M10 3 L8 21 M16 3 L14 21" />
        </g>
    ),
    queues: (
        <g fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinejoin="round">
            <path d="M12 3 L21 8 L12 13 L3 8 Z" />
            <path d="M3 13 L12 18 L21 13" />
            <path d="M3 17 L12 22 L21 17" opacity="0.6" />
        </g>
    ),
    webhooks: (
        <g fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
            <circle cx="6" cy="6" r="2.5" />
            <circle cx="18" cy="6" r="2.5" />
            <circle cx="12" cy="18" r="2.5" />
            <path d="M8 7 L11 16 M16 7 L13 16" />
        </g>
    ),
    cron: (
        <g fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round">
            <circle cx="12" cy="12" r="9" />
            <path d="M12 7 V12 L15.5 14" />
        </g>
    ),
    analytics: (
        <g fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round">
            <path d="M3 21 H21" />
            <path d="M6 17 V11 M11 17 V7 M16 17 V13 M21 17 V5" />
        </g>
    ),
    payments: (
        <g fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinejoin="round">
            <rect x="2.5" y="6" width="19" height="13" rx="1.5" />
            <path d="M2.5 10 H21.5" />
            <path d="M6 15 H10" strokeLinecap="round" />
        </g>
    ),
    tracking: (
        <g fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round">
            <circle cx="12" cy="12" r="9" />
            <circle cx="12" cy="12" r="4.5" />
            <circle cx="12" cy="12" r="1" fill="currentColor" />
            <path d="M12 1 V4 M12 20 V23 M1 12 H4 M20 12 H23" />
        </g>
    ),
    rbac: (
        <g fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round">
            <circle cx="7" cy="12" r="4" />
            <path d="M11 12 H22 M19 12 V16 M16 12 V14" />
        </g>
    ),
    email: (
        <g fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round">
            <rect x="2.5" y="5" width="19" height="14" rx="1.5" />
            <path d="m21 7-9 6-9-6" />
        </g>
    ),
    // Blade — no official brand mark; two overlapping chevrons referencing
    // the blade/template-layer concept from Laravel's view engine.
    blade: (
        <g fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round">
            <path d="M4 7 L12 3 L20 7 L12 11 Z" />
            <path d="M4 13 L12 9 L20 13 L12 17 Z" />
            <path d="M8 19 L12 21 L16 19" />
        </g>
    ),
    // CachyOS — simplified from official SVG: hexagonal body with
    // three accent circles (top-right corner decoration).
    cachyos: (
        <g fill="currentColor">
            {/* Main hexagon body */}
            <path d="M6.5 2h5.5l4 6.9-4 7.1H6.5l-4-7.1Z" fill="none" stroke="currentColor" strokeWidth="1.4" strokeLinejoin="round" />
            {/* Inner highlight line (left edge) */}
            <path d="M6.5 2 2.5 8.9l4 7.1" fill="none" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round" />
            {/* Three accent circles top-right */}
            <circle cx="17.5" cy="5" r="1.1" />
            <circle cx="15.5" cy="3" r="0.8" />
            <circle cx="18.5" cy="3" r="0.5" />
        </g>
    ),
    // LinkedIn — hand-drawn (simple-icons removed it for trademark reasons).
    // Stylised "in" mark inside a rounded square.
    linkedin: (
        <g fill="currentColor">
            <rect x="2" y="2" width="20" height="20" rx="2" fill="none" stroke="currentColor" strokeWidth="1.4" />
            <rect x="5.5" y="9" width="2.6" height="9" />
            <circle cx="6.8" cy="6.6" r="1.5" />
            <path d="M10.5 9 H13 V10.4 H13.05 C13.5 9.55 14.6 8.85 16 8.85 C18.7 8.85 19 10.6 19 12.95 V18 H16.4 V13.5 C16.4 12.45 16.4 11.05 14.95 11.05 C13.5 11.05 13.25 12.2 13.25 13.4 V18 H10.5 Z" />
        </g>
    ),
};

const ALIASES = {
    'react.js': 'react',
    reactjs: 'react',
    'vue.js': 'vue',
    vuejs: 'vue',
    nuxt: 'vue',
    'nuxt.js': 'vue',
    'next.js': 'nextjs',
    next: 'nextjs',
    node: 'nodejs',
    'node.js': 'nodejs',
    ts: 'typescript',
    js: 'javascript',
    framer: 'motion',
    'framer motion': 'motion',
    'framer-motion': 'motion',
    sql: 'mysql',
    sqlite: 'mysql',
    mariadb: 'mysql',
    postgres: 'postgresql',
    'postgres sql': 'postgresql',
    mongo: 'mongodb',
    cache: 'redis',
    memcached: 'redis',
    'role-based access': 'rbac',
    'role based access': 'rbac',
    rest: 'api',
    'rest api': 'api',
    gitlab: 'git',
    bitbucket: 'git',
    alpine: 'alpinejs',
    'alpine.js': 'alpinejs',
    'blade template': 'blade',
    'laravel blade': 'blade',
    'android studio': 'androidstudio',
    ci: 'codeigniter',
    html5: 'html',
    css3: 'css',
    'jetpack compose': 'jetpackcompose',
    arch: 'archlinux',
    'arch linux': 'archlinux',
    sveltekit: 'svelte',
    'svelte.js': 'svelte',
    cachy: 'cachyos',
    'cachy os': 'cachyos',
};

function normalize(name) {
    if (!name) return '';
    const key = String(name).trim().toLowerCase();
    return ALIASES[key] ?? key;
}

export default function BrandIcon({ name, className = 'size-3.5' }) {
    const key = normalize(name);
    const brandPath = BRAND_PATHS[key];

    if (brandPath) {
        return (
            <svg viewBox="0 0 24 24" aria-hidden="true" className={className} style={{ flexShrink: 0 }}>
                <path d={brandPath} fill="currentColor" />
            </svg>
        );
    }

    const concept = CONCEPT_ICONS[key];
    if (concept) {
        return (
            <svg viewBox="0 0 24 24" aria-hidden="true" className={className} style={{ flexShrink: 0 }}>
                {concept}
            </svg>
        );
    }

    // Fallback: 2-letter mono monogram
    const mono = String(name ?? '?').replace(/[^a-z0-9]/gi, '').slice(0, 2).toUpperCase() || '?';

    return (
        <svg viewBox="0 0 24 24" aria-hidden="true" className={className} style={{ flexShrink: 0 }}>
            <rect x="2" y="4" width="20" height="16" fill="none" stroke="currentColor" strokeWidth="1.2" />
            <text
                x="12"
                y="15.5"
                textAnchor="middle"
                fontFamily="ui-monospace, 'JetBrains Mono', monospace"
                fontSize="9"
                fontWeight="700"
                letterSpacing="0.4"
                fill="currentColor"
                stroke="none"
            >
                {mono}
            </text>
        </svg>
    );
}

export const ICON_KEYS = [...Object.keys(BRAND_PATHS), ...Object.keys(CONCEPT_ICONS)];
export const ICON_ALIASES = ALIASES;

export function resolveIconKey(name) {
    return normalize(name);
}
