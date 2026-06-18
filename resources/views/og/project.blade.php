@php
    $isClosed = $isClosed ?? false;
    $bgStart = $isClosed ? '#111110' : '#ffffff';
    $bgMid = $isClosed ? '#16150f' : '#faf9f6';
    $bgEnd = $isClosed ? '#0b0b0a' : '#f3f1ec';
    $ink = $isClosed ? '#f4f4f5' : '#09090b';
    $inkSoft = $isClosed ? '#a1a1aa' : '#71717a';
    $inkFaint = $isClosed ? '#3f3f46' : '#c4c1ba';
    $ring = $isClosed ? '#2a2a26' : '#e7e5e0';
    $ring2 = $isClosed ? '#23231f' : '#dedcd6';
    $chipBg = $isClosed ? '#1d1d18' : '#ffffff';
    $chipStroke = $isClosed ? '#33332c' : '#e7e5e0';
    $accent = $isClosed ? '#f59e0b' : '#09090b';
    $e = fn ($v) => htmlspecialchars((string) $v, ENT_QUOTES | ENT_XML1, 'UTF-8');
@endphp
<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="630" viewBox="0 0 1200 630">
    <defs>
        <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color="{{ $bgStart }}"/>
            <stop offset="0.5" stop-color="{{ $bgMid }}"/>
            <stop offset="1" stop-color="{{ $bgEnd }}"/>
        </linearGradient>
        <radialGradient id="glow" cx="0.78" cy="0.30" r="0.62">
            <stop offset="0" stop-color="{{ $isClosed ? '#3f3f46' : '#ffffff' }}" stop-opacity="{{ $isClosed ? '0.18' : '0.9' }}"/>
            <stop offset="1" stop-color="#ffffff" stop-opacity="0"/>
        </radialGradient>
    </defs>

    <rect width="1200" height="630" fill="url(#bg)"/>
    <rect width="1200" height="630" fill="url(#glow)"/>

    <g transform="translate(880,300)" fill="none">
        <circle r="250" stroke="{{ $ring }}" stroke-width="2"/>
        <circle r="180" stroke="{{ $ring2 }}" stroke-width="2" stroke-dasharray="5 9"/>
        <circle r="110" stroke="{{ $ring }}" stroke-width="2"/>
        <circle cx="0" cy="0" r="24" fill="{{ $accent }}"/>
        <circle cx="250" cy="0" r="9" fill="{{ $inkSoft }}"/>
        <circle cx="-127" cy="127" r="7" fill="{{ $inkSoft }}"/>
        <circle cx="78" cy="-163" r="6" fill="{{ $inkFaint }}"/>
        <circle cx="-180" cy="-12" r="5" fill="{{ $inkFaint }}"/>
    </g>

    <g transform="translate(96,0)">
        <text x="0" y="120" font-family="'Instrument Sans','Segoe UI',sans-serif" font-size="21" letter-spacing="6" fill="{{ $inkSoft }}" font-weight="600">
            {{ $e($isClosed ? 'CLASSIFIED ORBIT' : 'PUBLIC ORBIT') }}
        </text>

        @php($ty = 215)
        @foreach ($titleLines as $line)
            <text x="0" y="{{ $ty }}" font-family="'Instrument Sans','Segoe UI',sans-serif" font-size="82" font-weight="700" letter-spacing="-3" fill="{{ $ink }}">{{ $e($line) }}</text>
            @php($ty += 92)
        @endforeach

        @if (!empty($meta))
            <text x="0" y="{{ $ty + 6 }}" font-family="'Instrument Sans','Segoe UI',sans-serif" font-size="26" letter-spacing="2" fill="{{ $inkSoft }}" font-weight="500">{{ $e($meta) }}</text>
            @php($ty += 52)
        @endif

        @php($dy = $ty + 34)
        @foreach ($descriptionLines as $line)
            <text x="0" y="{{ $dy }}" font-family="'Instrument Sans','Segoe UI',sans-serif" font-size="28" fill="{{ $inkSoft }}">{{ $e($line) }}</text>
            @php($dy += 40)
        @endforeach

        @php($cx = 0)
        @php($chipY = 512)
        @foreach ($stack as $item)
            @php($w = 26 + mb_strlen($item) * 15)
            @if ($cx + $w < 690)
                <g transform="translate({{ $cx }},{{ $chipY }})">
                    <rect x="0" y="0" rx="20" ry="20" width="{{ $w }}" height="42" fill="{{ $chipBg }}" stroke="{{ $chipStroke }}" stroke-width="1.5"/>
                    <text x="{{ $w / 2 }}" y="28" text-anchor="middle" font-family="'Instrument Sans','Segoe UI',sans-serif" font-size="20" fill="{{ $ink }}" font-weight="500">{{ $e($item) }}</text>
                </g>
                @php($cx += $w + 14)
            @endif
        @endforeach

        <text x="0" y="588" font-family="'Instrument Sans','Segoe UI',sans-serif" font-size="22" letter-spacing="3" fill="{{ $inkFaint }}" font-weight="600">{{ $e(config('app.name', 'viasco prjkt.')) }}</text>
    </g>
</svg>
