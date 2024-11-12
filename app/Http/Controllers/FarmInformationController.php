<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FarmInformation;

class FarmInformationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'feedingType' => 'required|string',
            'frequencyOfFeeding' => 'required|string',
            'minPricePerKilo' => 'required|numeric',
            'maxPricePerKilo' => 'required|numeric',
            'location.lat' => 'required|numeric',
            'location.lng' => 'required|numeric',
        ]);

        FarmInformation::create([
            'feeding_type' => $request->feedingType,
            'frequency_of_feeding' => $request->frequencyOfFeeding,
            'min_price_per_kilo' => $request->minPricePerKilo,
            'max_price_per_kilo' => $request->maxPricePerKilo,
            'latitude' => $request->location['lat'],
            'longitude' => $request->location['lng'],
        ]);

        return response()->json(['message' => 'Farm information saved successfully']);
    }
}