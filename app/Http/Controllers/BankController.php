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

    // Save Branch
    public function storeBranch(Request $request)
    {
        $request->validate([
            'bank_id' => 'required|exists:banks,id',
            'branch_name' => 'required'
        ]);

        $branch = BankBranch::create([
            'bank_id' => $request->bank_id,
            'branch_name' => $request->branch_name
        ]);

        return response()->json($branch);
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