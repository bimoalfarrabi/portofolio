<x-mail::message>
# Pesan collab baru

Ada pesan baru dari section **Let's collab**.

**Nama:** {{ $portfolioMessage->name }}
**Email:** {{ $portfolioMessage->email }}
**Diterima:** {{ $portfolioMessage->created_at?->format('d M Y H:i') }}

<x-mail::panel>
{{ $portfolioMessage->message }}
</x-mail::panel>

<x-mail::button :url="'mailto:' . $portfolioMessage->email">
Balas {{ $portfolioMessage->name }}
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
