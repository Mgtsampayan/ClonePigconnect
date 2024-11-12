<?php
namespace App\Http\Controllers;

use App\Models\BreedingRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BreedingRecordController extends Controller
{
    public function index()
    {
        $breedingRecords = BreedingRecord::with(['sow', 'boar'])->get();
        return response()->json($breedingRecords);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sow_id' => 'required|exists:pigs,PigId', // Use 'PigId' as the primary key
            'boar_id' => 'required|exists:pigs,PigId', // Use 'PigId' as the primary key
            'date_of_breeding' => 'required|date',
            'expected_farrowing_date' => 'required|date',
        ]);

        // Get the authenticated user's ID
        $userId = Auth::id();

        // Create the breeding record with the authenticated user's ID
        $breedingRecord = BreedingRecord::create([
            'sow_id' => $request->sow_id,
            'boar_id' => $request->boar_id,
            'date_of_breeding' => $request->date_of_breeding,
            'expected_farrowing_date' => $request->expected_farrowing_date,
            'user_id' => $userId, // Add the authenticated user's ID
        ]);

        return response()->json($breedingRecord, 201);
    }

    public function show($id)
    {
        $breedingRecord = BreedingRecord::with(['sow', 'boar'])->findOrFail($id);
        return response()->json($breedingRecord);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sow_id' => 'required|exists:pigs,PigId', // Use 'PigId' as the primary key
            'boar_id' => 'required|exists:pigs,PigId', // Use 'PigId' as the primary key
            'date_of_breeding' => 'required|date',
            'expected_farrowing_date' => 'required|date',
        ]);

        $breedingRecord = BreedingRecord::findOrFail($id);
        $breedingRecord->update($request->all());

        return response()->json($breedingRecord);
    }

    public function destroy($id)
    {
        BreedingRecord::destroy($id);
        return response()->json(null, 204);
    }
}