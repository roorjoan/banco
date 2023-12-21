<?php

namespace App\Http\Controllers;

use App\Events\AccountEvent;
use App\Http\Requests\AccountDepositWithdrawalRequest;
use App\Http\Requests\AccountStoreRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Account;

class AccountController extends Controller
{
    /**
     * @group Cuenta
     * Crear una nueva cuenta bancaria
     *
     * @bodyParam amount numeric Balance inicial de la cuenta.
     *
     * @response 201 {
     *     "id": 1,
     *     "amount": 1000.00,
     *     "created_at": "2023-01-01T12:00:00Z",
     *     "updated_at": "2023-01-01T12:00:00Z"
     * }
     *
     * @param  \App\Http\Requests\AccountStoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AccountStoreRequest $request)
    {
        $account = Account::create($request->validated());

        return response()->json($account, 201);
    }

    /**
     * @group Transacción
     * Realizar un depósito
     *
     * @bodyParam id integer required Número de la cuenta.
     * @bodyParam amount numeric required Cantidad a depositar.
     *
     * @response 200 {
     *     "operation": "deposit",
     *     "account": 1,
     *     "balance": 1200.00
     * }
     * @response 404 {
     *     "message": "Account not found."
     * }
     *
     * @param  \App\Http\Requests\AccountDepositWithdrawalRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * @group Transacción
     * Realizar un retiro
     *
     * @bodyParam id integer required Número de la cuenta.
     * @bodyParam amount numeric required Cantidad a retirar.
     *
     * @response 200 {
     *     "operation": "withdrawal",
     *     "account": 1,
     *     "balance": 800.00
     * }
     * @response 404 {
     *     "message": "Account not found."
     * }
     * @response 500 {
     *     "message": "You cannot withdraw that amount."
     * }
     *
     * @param  \App\Http\Requests\AccountDepositWithdrawalRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * @group Cuenta
     * Consultar el saldo de una cuenta
     *
     * @urlParam id integer required Número de la cuenta.
     *
     * @response 200 {
     *     "balance": 1200.00
     * }
     * @response 404 {
     *     "message": "Account not found."
     * }
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function balance($id)
    {
        $account = Account::find($id);

        if (is_null($account)) {
            return response()->json(['message' => 'Account not found.'], 404);
        }

        return response()->json(['balance' => $account->amount], 200);
    }

    /**
     * @group Transacción
     * Consultar el historial de transacciones
     *
     * @urlParam id integer required Número de la cuenta.
     *
     * @response 200 {[
     *      {
     *          "operation": "deposit",
     *          "account": 2,
     *          "amount": 5000,
     *          "operation_date": "20-12-2023 21:42:46"
     *      },
     *      {
     *          "operation": "deposit",
     *          "account": 2,
     *          "amount": 15000,
     *          "operation_date": "20-12-2023 21:45:13"
     *      }
     * ]}
     * @response 404 {
     *     "message": "Account not found."
     * }
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function transactions($id)
    {
        $account = Account::with('transactions')->find($id);

        if (is_null($account)) {
            return response()->json(['message' => 'Account not found.'], 404);
        }

        return TransactionResource::collection($account->transactions);
    }
}
