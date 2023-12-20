<?php

namespace App\Listeners;

use App\Events\AccountEvent;
use App\Models\Transaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TransactionListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AccountEvent $event): void
    {
        Transaction::create([
            'operation' => $event->operation,
            'account_id' => $event->account->id,
            'amount' => $event->amount,
        ]);
    }
}
