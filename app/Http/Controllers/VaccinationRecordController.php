<?php
namespace App\Http\Controllers;

use App\Models\VaccinationRecord;
use Illuminate\Http\Request;

class VaccinationRecordController extends Controller
{
    public function index($pigId)
    {
        return response()->json(VaccinationRecord::where('pigId', $pigId)->get());
    }

    public function store(Request $request, $pigId)
    {
        $request->validate([
            'vaccineName' => 'required|string|max:255',
            'vaccineType' => 'required|string|max:255',
            'dateAdministered' => 'required|date',
        ]);

        $record = VaccinationRecord::create([
            'pigId' => $pigId,
            'vaccineName' => $request->vaccineName,
            'vaccineType' => $request->vaccineType,
            'dateAdministered' => $request->dateAdministered,
        ]);

        return response()->json($record, 201);
    }

    public function destroy($pigId, $id)
    {
        VaccinationRecord::where('pigId', $pigId)->where('id', $id)->delete();

        return response()->json(null, 204);
    }
}