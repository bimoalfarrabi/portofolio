# Design System — "Starfield NASA-punk"

Panduan gaya visual & guideline UI/UX untuk portofolio ini. Tema: instrumen pesawat antariksa ala menu game **Starfield** — light theme, monokrom + satu aksen, hairline grid, label engineering monospace. Bukan dekoratif; setiap elemen terasa seperti **readout instrumen**, bukan halaman brosur.

Sumber kebenaran token ada di `resources/css/app.css` (`@theme`). Dokumen ini menjelaskan maksud, aturan pakai, dan pantangan.

## 1. Prinsip

1. **Light, near-white, flat.** Permukaan off-white solid. Tidak ada dark mode di sisi publik (modal proyek punya mode dark khusus, itu pengecualian terkontrol).
2. **Monokrom + 1 aksen.** Mayoritas ink/abu di atas off-white, vermilion sebagai satu titik fokus per section.
3. **Solid, bukan transparan.** Tidak ada `bg-white/70 + backdrop-blur`. Translusensi memicu warna bocor & color-shift saat scroll.
4. **Hairline, bukan gradient.** Garis 1px deterministik. Gradient besar memicu banding; halo shadow memicu kesan kotor.
5. **Instrumen, bukan hiasan.** Angka, label monospace, tick mark, koordinat. Tiap section punya kode (`SYS_*`, `CH_*`) seperti panel kontrol.
6. **Gerak menahan diri.** Reveal sekali jalan, easing tegas, durasi pendek. Animasi tidak boleh memicu flicker atau lag (lihat §7).

## 2. Warna

Semua warna dideklarasikan sebagai Tailwind theme token (`--color-*`), dipakai via util `bg-*`, `text-*`, `border-*`.

### Surfaces (off-white, warm bias halus)
| Token | Hex | Pakai untuk |
| --- | --- | --- |
| `surface-0` | `#F5F5F3` | Background section/body |
| `surface-1` | `#FBFBFA` | Permukaan card/frame, input |
| `surface-2` | `#EDEDEB` | Panel sekunder, readout, hover-fill |
| `surface-3` | `#E2E2DF` | Permukaan paling dalam/aktif |

Surface sengaja near-neutral (bukan putih murni) agar tidak ada color-shift di layar wide-gamut saat scroll vs idle.

### Ink (foreground)
| Token | Hex | Pakai untuk |
| --- | --- | --- |
| `ink` | `#15140F` | Teks utama, judul, tombol solid |
| `ink-soft` | `#3A3833` | Teks sekunder kuat |
| `ink-mute` | `#6F6B5F` | Body text, label, caption |
| `ink-faint` | `#A8A39A` | Teks tersier, judul "redup" |

### Lines
| Token | Hex | Pakai untuk |
| --- | --- | --- |
| `line` | `#C8C6BF` | Border default, grid hairline |
| `line-strong` | `#1C1C1A` | Corner tick `.frame`, garis tegas |

### Warna semantik — empat, JANGAN digabung
| Token | Hex | Makna tunggal |
| --- | --- | --- |
| `accent` (vermilion) | `#E2533F` | Brand, fokus, highlight, satu kata anchor per judul, dot aktif |
| `success` (forest) | `#3F8A4E` | Available, outcome, pesan sukses |
| `warn` (burnt sienna) | `#8A4F22` | Error validasi form |
| `red-600` | `#dc2626` | Destruktif saja (hapus, dialog konfirmasi) |

Tiap warna punya `-soft` & `-deep` untuk fill/teks di atas fill. Vermilion ≠ warn ≠ red: jaga tetap terpisah agar makna tidak ambigu. Hijau dipakai untuk "hal baik/tidak error" (available dot, outcome di modal).

### Aturan aksen
- **Satu anchor vermilion per judul section.** Sisanya ink/ink-faint.
- Aksen juga boleh untuk: ring fokus, dot status, hover ikon, blip.
- Jangan menebar aksen ke banyak elemen dalam satu layar; nilai fokusnya hilang.

## 3. Tipografi

| Peran | Font | Catatan |
| --- | --- | --- |
| Sans | `Instrument Sans` (`--font-sans`) | Judul & body. Tracking rapat di judul besar (`tracking-[-0.05em]`). |
| Mono | `JetBrains Mono` (`--font-mono`) | Label engineering, angka, kode, koordinat, timestamp. |

Font dimuat dari bunny.net (privacy-friendly, lihat `welcome.blade.php`).

Pola judul section: ukuran fluid `text-[clamp(2.4rem,6vw,4.4rem)]`, `font-semibold`, `leading-[0.98]`, `tracking-[-0.05em]`. Baris kedua sering `text-ink-faint` dengan satu kata `text-accent`.

```jsx
<h2 className="text-[clamp(2.4rem,6vw,4.4rem)] font-semibold leading-[0.98] tracking-[-0.05em] text-ink">
    Tiny stats,
    <span className="block text-ink-faint">actual <span className="text-accent">signal</span>.</span>
</h2>
```

## 4. Utility class (di `app.css`)

| Class | Fungsi |
| --- | --- |
| `.eng-label` | Label engineering: mono 11px, `tracking-[0.28em]`, uppercase, `ink-mute`. Dipakai di header tiap section. |
| `.frame` | Card permukaan solid + corner tick. Tanpa translusensi, tanpa halo. |
| `.frame-corner.bl` / `.br` | Tick sudut bawah-kiri / bawah-kanan (sudut atas via `::before/::after`). |
| `.grid-hairline` | Grid 56px, garis 1px deterministik. |
| `.grid-hairline-soft` | Grid 28px lebih halus. |
| `.tick-bar` | Deretan tick vertikal (vibe penggaris instrumen). |
| `.starfield-blip` | Animasi kedip opacity 1.6s (dot status). |
| `.progress-shimmer` | Shimmer transform untuk progress bar (hormati `prefers-reduced-motion`). |

Pola `.frame` lengkap:
```jsx
<motion.div className="frame relative ...">
    <span className="frame-corner bl" />
    <span className="frame-corner br" />
    {/* konten */}
</motion.div>
```

## 5. Pola komponen

- **Header section:** `.eng-label` dengan kode (`SYS_TELEMETRY · 07`) + judul + paragraf pendek `ink-mute`. Tiap section punya nomor urut.
- **Card/panel:** `.frame` atau `bg-surface-1` dengan `border border-line`. Grid tile pakai `gap-px border border-line bg-line` (garis = background yg bocor lewat gap 1px).
- **Tombol primer:** `bg-ink text-surface-1`, hover `bg-accent`, label uppercase `tracking-[0.18em]`, sering diawali kode mono `[01]`.
- **Tombol sekunder:** `border border-line bg-surface-1 text-ink`, hover `border-ink`.
- **Input:** `border border-line bg-surface-1`, focus `border-ink`; error `border-accent`/`warn`.
- **Status pill:** border + dot kecil; available pakai dot `success` + `starfield-blip`.
- **Koordinat/readout:** mono kecil di sudut card (`PUBLIC // ORBIT`, `N=12`, `R=44.33.22`).

## 6. Ikon

- Brand: `resources/js/components/shared/BrandIcon.jsx` memakai paket `simple-icons` untuk path resmi (React, Laravel, dst).
- Beberapa nama dihapus dari `simple-icons` karena trademark (mis. `siLinkedin`) — disediakan path hand-drawn. `email` pakai envelope generik.
- Ikon skill dipilih dari CMS (kolom `icon` di skills, mirror key di `config/skill_icons.php`), dengan live preview di admin.
- Stroke ikon hand-drawn: `strokeWidth` ~1.6–1.7, `strokeLinecap/Linejoin="round"`, `size-3.5`–`size-5`. Default `text-ink-faint`, hover `text-accent`.

## 7. Gerak & animasi (penting untuk performa)

Easing standar: `[0.16, 1, 0.3, 1]` (ease-out tegas). Durasi reveal 0.5–0.9s.

Aturan keras hasil debugging:

1. **Reveal card pakai transform-only, BUKAN opacity.** Card duduk di atas background bergerak (canvas `StitchBackground`/`AstronautDots`, grid hairline, garis 1px antar tile). Fade `opacity 0→1` membuat background bocor tiap frame = flicker. Untuk container/elemen "card", animasikan `y`/`x`/`scale` saja, biarkan opacity tetap 1. Teks di atas permukaan solid boleh fade.
2. **Pre-hide reveal teks.** `[data-reveal] { opacity: 0 }` di `@layer base` mencegah flash first-paint sebelum Motion commit. Pakai `data-reveal` hanya pada elemen yg memang fade.
3. **Hover JANGAN animasikan `background-color`.** Interpolasi warna paint-bound → patah saat hover-out (frame drop tak tertutup gerak kursor). Sandarkan feedback hover ke transform (scale dot, translate arrow) atau perubahan warna ikon/teks yg murah. Hindari juga overlay opacity absolut di atas elemen berlapis (menambah composite layer, malah berat).
4. **Animasi idle infinit** (blip, pulse, radar) harus pause saat off-screen/tab tersembunyi (lihat `StitchBackground` IntersectionObserver + visibilitychange).
5. **Hormati `prefers-reduced-motion`** untuk animasi non-esensial.

## 8. Pantangan (anti-pattern)

- Translusensi + `backdrop-blur` pada permukaan (bocor warna, color-shift scroll).
- Gradient besar / halo shadow di belakang card (banding, kotor).
- Fade opacity pada card di atas background bergerak (flicker).
- Animasi `background-color` pada hover (lag hover-out).
- Lebih dari satu aksen vermilion bersaing dalam satu layar.
- Putih murni `#fff` sebagai surface utama (color-shift wide-gamut).
- Dark mode di sisi publik (kecuali modal proyek yg memang dirancang dark).

## 9. Checklist saat menambah section baru

- [ ] Background `bg-surface-0`, opsional `grid-hairline`/`-soft` dengan opacity rendah.
- [ ] Header: `.eng-label` + kode `SYS_* · NN` + judul dengan satu kata `text-accent`.
- [ ] Card pakai `.frame` atau `bg-surface-1 border border-line`; grid tile pakai `gap-px bg-line`.
- [ ] Reveal card transform-only; teks boleh fade + `data-reveal`.
- [ ] Hover via transform/warna ikon, bukan `background-color`.
- [ ] Warna semantik dipakai sesuai makna tunggalnya.
- [ ] Animasi idle pause saat off-screen; hormati reduced-motion.
- [ ] Build hijau: `npm run build`.
