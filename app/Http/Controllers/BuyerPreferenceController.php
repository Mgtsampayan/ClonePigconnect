<?php

namespace App\Http\Controllers;

use App\Models\BuyerPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyerPreferenceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'preferred_weight' => 'required|string|max:255',
            'age_of_pigs' => 'required|string|max:255',
            'price_range' => 'required|string|max:255',
        ]);

        BuyerPreference::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only('preferred_weight', 'age_of_pigs', 'price_range')
        );

        return response()->json(['message' => 'Preferences saved successfully.']);
    }

    public function show()
    {
        $preference = BuyerPreference::where('user_id', Auth::id())->first();
        return response()->json($preference);
    }
}