<?php

namespace App\Http\Controllers;

use App\Events\AccountEvent;
use App\Http\Requests\AccountDepositWithdrawalRequest;
use App\Http\Requests\AccountStoreRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Account;

class AccountController extends Controller
{
    public function store(AccountStoreRequest $request)
    {
        $account = Account::create($request->validated());

        return response()->json($account, 201);
    }

    public function deposit(AccountDepositWithdrawalRequest $request)
    {
        $account = Account::find($request->id);
        $amount = $request->amount;

        if (is_null($account)) {
            return response()->json(['message' => 'Account not found.'], 404);
        }

        $account->amount += $amount;
        $account->save();

        AccountEvent::dispatch($account, 'deposit', $amount);

        return response()->json([
            'operation' => 'deposit',
            'account' => $account->id,
            'balance' => $account->amount,
        ], 200);
    }

    public function withdrawal(AccountDepositWithdrawalRequest $request)
    {
        $account = Account::find($request->id);
        $amount = $request->amount;

        if (is_null($account)) {
            return response()->json(['message' => 'Account not found.'], 404);
        } elseif (($account->amount - $amount) < 0) {
            return response()->json(['message' => 'You cannot withdraw that amount.'], 500);
        }

        $account->amount -= $amount;
        $account->save();

        AccountEvent::dispatch($account, 'withdrawal', $amount);

        return response()->json([
            'operation' => 'withdrawal',
            'account' => $account->id,
            'balance' => $account->amount,
        ], 200);
    }

    public function balance($id)
    {
        $account = Account::find($id);

        if (is_null($account)) {
            return response()->json(['message' => 'Account not found.'], 404);
        }

        return response()->json(['balance' => $account->amount], 200);
    }

    public function transactions($id)
    {
        $account = Account::with('transactions')->find($id);

        if (is_null($account)) {
            return response()->json(['message' => 'Account not found.'], 404);
        }

        return TransactionResource::collection($account->transactions);
    }
}
