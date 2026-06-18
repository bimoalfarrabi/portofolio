<?php

namespace App\Http\Controllers;

use App\Services\HolidayService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;

class HolidayController extends Controller
{
    public function index(HolidayService $holidays): JsonResponse
    {
        try {
            return response()->json($holidays->all());
        } catch (RequestException) {
            return response()->json([
                'status' => 'error',
                'data' => [],
            ], 502);
        }
    }
}
