<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FlightsApiController extends Controller
{
    public function index(Request $request)
    {
        // Входные параметры
        $origin = $request->query('origin');
        $destination = $request->query('destination');
        $depart_at = $request->query('depart_at');
        $return_at = $request->query('return_at');

        if (!$origin || !$destination || !$depart_at) {
            return response()->json([
                'error' => 'origin, destination и depart_at обязательны'
            ], 400);
        }

        $token = env('AVIASALES_API_KEY');

        $url = 'https://api.travelpayouts.com/aviasales/v3/prices_for_dates';

        $params = [
            'origin' => $origin,
            'destination' => $destination,
            'departure_at' => $depart_at,
            'currency' => 'rub',
            'token' => $token,
            'one_way' => 'false',   // false если нужен обратно
        ];

        if ($return_at) {
            $params['return_at'] = $return_at;
        }

        try {
            $response = Http::get($url, $params);

            if ($response->failed()) {
                return response()->json([
                    'error' => 'Ошибка API',
                    'body' => $response->body()
                ], 500);
            }

            $data = $response->json();

            $flights = collect($data['data'] ?? [])->map(function ($flight) {
                return [
                    'origin' => $flight['origin'],
                    'destination' => $flight['destination'],
                    'price' => $flight['price'],
                    'airline' => $flight['airline'] ?? null,
                    'flight_number' => $flight['flight_number'] ?? null,
                    'departure_at' => $flight['departure_at'] ?? null,
                    'return_at' => $flight['return_at'] ?? null,
                    'duration_to' => $flight['duration_to'] ?? null,
                    'duration_back' => $flight['duration_back'] ?? null,
                    'transfers' => $flight['transfers'] ?? null,
                    'return_transfers' => $flight['return_transfers'] ?? null,
                ];
            });

            return response()->json($flights);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ошибка при запросе к API авиабилетов',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
