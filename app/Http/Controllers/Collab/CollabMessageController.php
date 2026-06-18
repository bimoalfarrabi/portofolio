<?php

namespace App\Http\Controllers\Collab;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCollabMessageRequest;
use App\Mail\CollabMessageMail;
use App\Models\PortfolioCollab;
use App\Models\PortfolioMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class CollabMessageController extends Controller
{
    public function store(StoreCollabMessageRequest $request): JsonResponse|RedirectResponse
    {
        $key = 'collab-message:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $payload = [
                'message' => 'Terlalu banyak pesan. Coba lagi dalam ' . ceil($seconds / 60) . ' menit.',
            ];

            return $request->expectsJson()
                ? response()->json($payload, 429)
                : back()->withErrors(['message' => $payload['message']])->withInput();
        }

        RateLimiter::hit($key, 60 * 60);

        $data = $request->validated();

        $message = PortfolioMessage::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'message' => $data['message'],
            'ip_address' => $request->ip(),
        ]);

        $mailSent = false;
        $recipient = PortfolioCollab::current()->email;

        try {
            Mail::to($recipient)->send(new CollabMessageMail($message));
            $mailSent = true;
        } catch (\Throwable $e) {
            Log::warning('Collab mail send failed: ' . $e->getMessage());
        }

        $message->update(['mail_sent' => $mailSent]);

        $payload = [
            'message' => 'Pesan terkirim. Terima kasih sudah mengirim sinyal.',
        ];

        return $request->expectsJson()
            ? response()->json($payload, 201)
            : back()->with('status', $payload['message']);
    }
}
