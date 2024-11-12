<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;

class ExpenseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cost' => 'required|numeric',
            'date_of_purchase' => 'required|date',
            'type' => 'required|string|in:medication,food,transportation',
        ]);

        $expense = Expense::create([
            'name' => $request->name,
            'cost' => $request->cost,
            'date_of_purchase' => $request->date_of_purchase,
            'type' => $request->type,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($expense, 201);
    }

    public function index()
    {
        $expenses = Expense::where('user_id', auth()->id())->get();
        return response()->json($expenses);
    }
}