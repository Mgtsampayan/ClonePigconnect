<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    public function recommend()
    {
        $user = Auth::user();
        $fastApiUrl = 'http://127.0.0.1:8001/recommendations'; // Update this URL to your FastAPI server address
        $response = Http::get($fastApiUrl, [
            'user_id' => $user->id,
        ]);

        if ($response->failed()) {
            return response()->json([
                'message' => 'Failed to fetch recommendations',
                'status' => $response->status(),
                'error' => $response->body()
            ], 500);
        }

        return $response->json();
    }
    public function trackInteraction(Request $request)
    {
        $user = Auth::user();
        $fastApiUrl = 'http://127.0.0.1:8001/api/track_interaction'; // Update this URL to your FastAPI server address
        $response = Http::post($fastApiUrl, [
            'user_id' => $user->id,
            'pig_id' => $request->pig_id,
        ]);

        if ($response->failed()) {
            return response()->json([
                'message' => 'Failed to track interaction',
                'status' => $response->status(),
                'error' => $response->body()
            ], 500);
        }

        return response()->json(['message' => 'Interaction tracked successfully']);
    }
}