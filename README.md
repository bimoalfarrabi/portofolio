```
 ██████╗  ██████╗ ██████╗ ████████╗███████╗ ██████╗ ██╗     ██╗ ██████╗
██╔══██╗██╔═══██╗██╔══██╗╚══██╔══╝██╔════╝██╔═══██╗██║     ██║██╔═══██╗
██████╔╝██║   ██║██████╔╝   ██║   █████╗  ██║   ██║██║     ██║██║   ██║
██╔═══╝ ██║   ██║██╔══██╗   ██║   ██╔══╝  ██║   ██║██║     ██║██║   ██║
██║     ╚██████╔╝██║  ██║   ██║   ██║     ╚██████╔╝███████╗██║╚██████╔╝
╚═╝      ╚═════╝ ╚═╝  ╚═╝   ╚═╝   ╚═╝      ╚═════╝ ╚══════╝╚═╝ ╚═════╝
```

<div align="center">

**`SYS_PORTFOLIO · STARFIELD NASA-PUNK · v1.0`**

*Bukan halaman brosur. Ini panel kontrol.* &nbsp;·&nbsp; *Not a brochure. This is a control panel.*

[![ID](https://img.shields.io/badge/lang-ID-E2533F?style=flat-square)](#bahasa-indonesia)
[![EN](https://img.shields.io/badge/lang-EN-15140F?style=flat-square)](#english)

</div>

---

<a name="bahasa-indonesia"></a>
## 🇮🇩 Bahasa Indonesia

> Web portofolio personal dengan estetika NASA-punk — readout instrumen, hairline grid,
> label engineering monospace, dan satu aksen vermilion.
> Setiap elemen dirancang seperti **readout instrumen pesawat antariksa**, bukan dekorasi.

### `[01]` Stack

<div align="center">

![PHP](https://img.shields.io/badge/PHP_8.2+-777BB4?style=flat-square&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel_12-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![React](https://img.shields.io/badge/React_19-61DAFB?style=flat-square&logo=react&logoColor=black)
![TailwindCSS](https://img.shields.io/badge/Tailwind_v4-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)
![Vite](https://img.shields.io/badge/Vite_8-646CFF?style=flat-square&logo=vite&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-003B57?style=flat-square&logo=sqlite&logoColor=white)

</div>

| Layer | Tech |
|---|---|
| Backend | PHP 8.2+ · Laravel 12 |
| Frontend | React 19 · Tailwind CSS v4 |
| Animasi | Motion v12 (transform-only, tanpa flicker) |
| Ikon | simple-icons |
| Font | Instrument Sans · JetBrains Mono |
| Build | Vite 8 · laravel-vite-plugin |
| Database | SQLite (lokal) · MySQL (produksi) |

### `[02]` Instalasi

**Prasyarat:** PHP >= 8.2, Composer, Node.js >= 20

```bash
# — INIT ——————————————————————————————————————————
git clone <repo-url> portfolio && cd portfolio

# — DEPENDENCIES ——————————————————————————————————
composer install
npm install

# — ENVIRONMENT ———————————————————————————————————
cp .env.example .env
php artisan key:generate

# — DATABASE ——————————————————————————————————————
php artisan migrate

# — LAUNCH ————————————————————————————————————————
npm run dev &       # Vite HMR
php artisan serve   # → http://localhost:8000
```

**Production build:**

```bash
npm run build && php artisan optimize
```

### `[03]` Design System

Panduan lengkap visual — color tokens, tipografi, aturan animasi, pola komponen — ada di [`docs/design-system.md`](docs/design-system.md).

Prinsip utama:
- **Light, flat, solid** — off-white, tanpa `backdrop-blur`, tanpa gradient
- **Monokrom + 1 aksen** — vermilion `#E2533F` sebagai satu titik fokus
- **Instrumen, bukan hiasan** — angka, tick mark, koordinat, kode section
- **Gerak menahan diri** — transform-only reveal, easing tegas, durasi pendek

---

<a name="english"></a>
## 🇬🇧 English

> A personal portfolio website with a NASA-punk aesthetic — instrument readouts, hairline grids,
> monospace engineering labels, and a single vermilion accent.
> Every element is designed to feel like a **spacecraft instrument panel**, not decoration.

### `[01]` Stack

<div align="center">

![PHP](https://img.shields.io/badge/PHP_8.2+-777BB4?style=flat-square&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel_12-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![React](https://img.shields.io/badge/React_19-61DAFB?style=flat-square&logo=react&logoColor=black)
![TailwindCSS](https://img.shields.io/badge/Tailwind_v4-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)
![Vite](https://img.shields.io/badge/Vite_8-646CFF?style=flat-square&logo=vite&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-003B57?style=flat-square&logo=sqlite&logoColor=white)

</div>

| Layer | Tech |
|---|---|
| Backend | PHP 8.2+ · Laravel 12 |
| Frontend | React 19 · Tailwind CSS v4 |
| Animation | Motion v12 (transform-only, no flicker) |
| Icons | simple-icons |
| Fonts | Instrument Sans · JetBrains Mono |
| Build | Vite 8 · laravel-vite-plugin |
| Database | SQLite (local) · MySQL (production) |

### `[02]` Installation

**Requirements:** PHP >= 8.2, Composer, Node.js >= 20

```bash
# — INIT ——————————————————————————————————————————
git clone <repo-url> portfolio && cd portfolio

# — DEPENDENCIES ——————————————————————————————————
composer install
npm install

# — ENVIRONMENT ———————————————————————————————————
cp .env.example .env
php artisan key:generate

# — DATABASE ——————————————————————————————————————
php artisan migrate

# — LAUNCH ————————————————————————————————————————
npm run dev &       # Vite HMR
php artisan serve   # → http://localhost:8000
```

**Production build:**

```bash
npm run build && php artisan optimize
```

### `[03]` Design System

Full visual guide — color tokens, typography, animation rules, component patterns — in [`docs/design-system.md`](docs/design-system.md).

Core principles:
- **Light, flat, solid** — off-white surfaces, no `backdrop-blur`, no gradients
- **Monochrome + 1 accent** — vermilion `#E2533F` as the single focal point
- **Instrument, not ornament** — numbers, tick marks, coordinates, section codes
- **Restrained motion** — transform-only reveals, sharp easing, short durations

---

<div align="center">

`// END OF TRANSMISSION`

</div>
