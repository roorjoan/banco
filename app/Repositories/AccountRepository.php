<?php

namespace App\Repositories;

use App\Events\AccountEvent;
use App\Exceptions\AccountNotFoundException;
use App\Exceptions\InvalidOperationException;
use App\Models\Account;

class AccountRepository implements AccountRepositoryInterface
{
    public function storeAccount($request)
    {
        return Account::create($request);
    }

    public function depositTransaction($request)
    {
        $account = Account::find($request->id);
        $amount = $request->amount;

        if (is_null($account)) {
            throw new AccountNotFoundException();
        }

        $account->amount += $amount;
        $account->save();

        AccountEvent::dispatch($account, 'deposit', $amount);

        return $account;
    }

    public function withdrawalTransaction($request)
    {
        $account = Account::find($request->id);
        $amount = $request->amount;

        if (is_null($account)) {
            throw new AccountNotFoundException();
        } elseif (($account->amount - $amount) < 0) {
            throw new InvalidOperationException();
        }

        $account->amount -= $amount;
        $account->save();

        AccountEvent::dispatch($account, 'withdrawal', $amount);

        return $account;
    }

    public function balanceAccount($id)
    {
        $account = Account::find($id);

        if (is_null($account)) {
            throw new AccountNotFoundException();
        }

        return $account->amount;
    }

    public function transactionsAccount($id)
    {
        $account = Account::with('transactions')->find($id);

        if (is_null($account)) {
            throw new AccountNotFoundException();
        }

        return $account->transactions;
    }
}
