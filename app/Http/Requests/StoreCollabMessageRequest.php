<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCollabMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:4000'],
            // Honeypot: harus kosong. Diisi = bot.
            'company' => ['prohibited'],
            // Min-time: detik sejak form render. Terlalu cepat = bot.
            'elapsed' => ['required', 'integer', 'min:3'],
        ];
    }

    public function messages(): array
    {
        return [
            'company.prohibited' => 'Submission ditolak.',
            'elapsed.min' => 'Form dikirim terlalu cepat, coba lagi.',
            'elapsed.required' => 'Form tidak valid, muat ulang halaman.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(response()->json([
                'message' => 'Periksa kembali isian form.',
                'errors' => $validator->errors(),
            ], 422));
        }

        parent::failedValidation($validator);
    }
}
