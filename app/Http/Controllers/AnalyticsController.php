<?php

namespace App\Http\Controllers;
use App\Models\Pig;
use App\Models\PigWeight;
use App\Models\PigFarmInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function getAnalytics()
    {
        $userId = Auth::id();
        
        // Total number of pigs
        $totalPigs = Pig::where('user_id', $userId)->count();
        
        // Average weight of pigs
        $averageWeight = Pig::where('user_id', $userId)->avg('weight');
      

        // Weight trend over time
        $weightTrend = PigWeight::selectRaw('DATE(date) as date, AVG(weight) as average_weight')
            ->whereHas('pig', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    

        return response()->json([
            'totalPigs' => $totalPigs,
            'averageWeight' => $averageWeight,
            'weightTrend' => $weightTrend,
        ]);
    }

    public function getWeather(Request $request)
    {
        $userId = Auth::id();
        $farmInfo = PigFarmInformation::where('user_id', $userId)->first();

        if (!$farmInfo) {
            return response()->json(['error' => 'Farm information not found'], 404);
        }

        $apiKey = '58bc032bc09b45d38ac05217241111';
        $lat = $farmInfo->latitude;
        $lon = $farmInfo->longitude;

        try {
            $response = Http::get("http://api.weatherapi.com/v1/current.json?key={$apiKey}&q={$lat},{$lon}");

            if ($response->failed()) {
                return response()->json(['error' => 'Failed to fetch weather data'], 500);
            }

            $weatherData = $response->json();

            return response()->json([
                'temperature' => $weatherData['current']['temp_c'],
                'weather' => $weatherData['current']['condition']['text'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching weather data'], 500);
        }
    }
}