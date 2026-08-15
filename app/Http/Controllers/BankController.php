<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
use App\Models\BankBranch;

class BankController extends Controller
{
    // Save Bank
    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|unique:banks,bank_name'
        ]);

        $bank = Bank::create([
            'bank_name' => $request->bank_name
        ]);

        return response()->json($bank);
    }

    // Save / Update Branch
    public function storeBranch(Request $request)
    {
        $request->validate([
            'bank_id' => 'required|exists:banks,id',
            'branch_name' => 'required|string|max:100',
            'account_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:100',
        ]);

        // If branch_id is provided, update existing branch
        if ($request->filled('branch_id')) {

            $branch = BankBranch::where('id', $request->branch_id)
                ->where('bank_id', $request->bank_id)
                ->firstOrFail();

            $branch->update([
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Account information updated successfully.',
                'branch' => $branch->fresh(),
            ]);
        }

        // Otherwise create new branch
        $branch = BankBranch::create([
            'bank_id' => $request->bank_id,
            'branch_name' => $request->branch_name,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Branch created successfully.',
            'branch' => $branch,
        ]);
    }

    // Load Branches
    public function branches(Bank $bank)
    {
        return response()->json(
            $bank->branches()->orderBy('branch_name')->get()
        );
    }

    public function getBanks()
    {
        return response()->json(
            Bank::orderBy('bank_name')->get()
        );
    }
}