<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\PigFarmInformation;
use App\Models\User;

class PigFarmInformationController extends Controller
{
    public function show(Request $request)
    {
        $farmInformation = PigFarmInformation::where('user_id', $request->user()->id)->first();

        return response()->json($farmInformation);
    } public function showBuyer(Request $request)
    {
        $farmInformation = PigFarmInformation::where('user_id', $request->user_id)->first();
        $userName = User::where('id', $request->user_id)->first();
 
        if ($farmInformation) {
            return response()->json([
                'min_price_per_kilo' => $farmInformation->min_price_per_kilo,
                'max_price_per_kilo' => $farmInformation->max_price_per_kilo,
                'location' => $farmInformation->location,
                'latitude' => $farmInformation->latitude,
                'longitude' => $farmInformation->longitude,
                'owner_name' => $userName->name, // Include owner's name
            ]);
        } else {
            return response()->json(['message' => 'Farm information not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'feedingType' => 'required|string',
            'frequencyOfFeeding' => 'required|string',
            'minPricePerKilo' => 'required|numeric',
            'maxPricePerKilo' => 'required|numeric',
            'address' => 'required|string'
        ]);

        $apiKey = 'AIzaSyAPE3z_ByaGmKAwUDjUPFP6ZEZyyWmKvTY';
        $geocodeUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($request->address) . "&key=" . $apiKey;

        $response = Http::get($geocodeUrl);
        if ($response->failed()) {
            return response()->json(['error' => 'Failed to geocode address'], 500);
        }

        $data = $response->json();
        if (empty($data['results'])) {
            return response()->json(['error' => 'No geocoding results found'], 404);
        }

        $position = $data['results'][0]['geometry']['location'];

        $data = [
            'user_id' => $request->user()->id,
            'feeding_type' => $request->feedingType,
            'frequency_of_feeding' => $request->frequencyOfFeeding,
            'min_price_per_kilo' => $request->minPricePerKilo,
            'max_price_per_kilo' => $request->maxPricePerKilo,
            'address' => $request->address,
            'latitude' => $position['lat'],
            'longitude' => $position['lng'],
        ];

        PigFarmInformation::updateOrCreate(
            ['user_id' => $request->user()->id],
            $data
        );

        return response()->json(['message' => 'Pig farm information saved successfully']);
    }
}